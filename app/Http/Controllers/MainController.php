<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use \Storage;
use GuzzleHttp\TransferStats;

use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleTor\Middleware;
use GuzzleHttp\Client;

use App\Jobs\GetPageSpeed;
use App\Jobs\JobDomainInfo;
use App\Jobs\JobDuplicateContent;
use App\Jobs\JobSearchEngineIndex;
use App\Jobs\JobKeywordFrequency;
use App\Jobs\JobSocialInteraction;
use App\Jobs\JobCrawler;

use App\Domain;
use App\QueueList;

use App\Helper\GlobalFunction as GF;
use App\Helper\QueueStatus;

use App\Library\GooglePageSpeed;
use App\Library\Crawler;
use App\Library\DomainInfo;
use App\Library\SearchEngine;
use App\Library\Majestic;
use App\Library\DuplicateContent;
use App\Library\SEOAssessment;

class MainController extends Controller
{
    protected $url;

    protected $current_id;

    protected $queue_id;

    protected $folder_name;

    protected $time;

    protected $final_url;

    /*
    * Main method
    * 1. Get url from user input (queue form)
    * 2. Parse url to get domain (domain name is also used for folder name)
    * 3. Check duplicate domain record in database
    * 4. If no duplicate found, make folder in /storage/app/result/folder_name
    * 5. Save domain id in the result table and get the current result id
    * 6. Save domain id in the queue list table and get the current queue id
    * 7. Process all jobs in the background (powered by Laravel Queue Service)
    * Reference: https://laravel.com/docs/5.2/queues
    */
    public function insertDomain(Request $request)
    {
      $id = 0;
      $result = array();

      //#1
      $this->url = 'http://'.$request->input('url');
      $this->getPreferredUrl();

      //Check if url is accessable
      if($this->final_url['code'] == 200)
      {
        //#2
        $this->folder_name = GF::parseUrl($this->url);
        $this->time = Carbon::now()->format('Y-m-d_H\'i\'s');

        //#3
        if($this->checkDomain(GF::parseUrl($this->url))['count'] == 0)
        {
          $domain = new Domain;
          $domain->url = $this->folder_name;
          $domain->save();
          //#4
          Storage::makeDirectory('result/'.$this->folder_name, 2775, true);
          $id = $domain->domain_id;
        }
        else
        {
          $id = $this->checkDomain(GF::parseUrl($this->url))['id'];
        }

        //#5
        /*$result = new Result;
        $result->domain_id = $id;
        $result->save();
        $this->current_id = $result->result_id;*/

        //#6
        $queue = new QueueList;
        $queue->domain_id = $id;
        $queue->overall_status = 0;
        $queue->save();
        $this->queue_id = $queue->queue_id;

        //#7
        $this->processAll();

        $result['code'] = 200;
        $result['id'] = $this->queue_id;

        return $result;
      }
      else
      {
        return $this->final_url;
      }
    }

    /*
    * Get Preferred URL from website
    */
    public function getPreferredUrl()
    {
      try{
        $this->final_url = array();
        $client = new \GuzzleHttp\Client([
          'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36',
          ],
          'verify' => false,
        ]);
        $client->request('GET', $this->url, [
            'on_stats' => function (TransferStats $stats) {
                if ($stats->hasResponse()) {
                    $statusCode = $stats->getResponse()->getStatusCode();
                    if($statusCode == 200)
                    {
                      $this->final_url['url'] = $stats->getEffectiveUri();
                      $this->final_url['code'] = $statusCode;
                      $this->url = $this->final_url['url']->getScheme().'://'.$this->final_url['url']->getHost().$this->final_url['url']->getPath();
                    }
                }
            }
        ]);
      }
      catch(\GuzzleHttp\Exception\RequestException $e)
      {
        $this->final_url['code'] = $e->getCode();
        $this->final_url['message'] = $e->getMessage();
      }
    }

    /*
    * Check duplicate domain in database
    */
    public function checkDomain($domain)
    {
      $result = array();
      $domain = Domain::where('url', $domain)->get();
      $result['count'] = count($domain);
      if(count($domain) > 0)
      {
        $result['id'] = $domain[0]->domain_id;
      }
      return $result;
    }

    public function processAll()
    {
      //$this->pageSpeed('desktop');
      //$this->pageSpeed('mobile');
      //$this->domainInfo();
      //$this->duplicateContent();
      //$this->searchIndex();
      //$this->keywordFrequency();
      //$this->socialInteraction();
      $this->crawler();
      //$this->searchIndex();
      //$this->crawler();
    }

    public function pageSpeed($strategy)
    {
      $job = (new GetPageSpeed(
                  $this->url,
                  $strategy,
                  $this->current_id,
                  $this->queue_id,
                  $this->folder_name,
                  $this->time
              ));

      $this->dispatch($job);

      if($strategy == 'desktop')
      {
        QueueStatus::show('pagespeed_desktop', $this->queue_id, 0);
      }
      else
      {
        QueueStatus::show('pagespeed_mobile', $this->queue_id, 0);
      }
    }

    public function domainInfo()
    {
      $job = (new JobDomainInfo(
                  $this->url,
                  $this->current_id,
                  $this->queue_id,
                  $this->folder_name,
                  $this->time
              ));

      $this->dispatch($job);
      QueueStatus::show('domain_info', $this->queue_id, 0);
    }

    public function duplicateContent()
    {
      $job = (new JobDuplicateContent(
                  $this->url,
                  $this->current_id,
                  $this->queue_id,
                  $this->folder_name,
                  $this->time
              ));

      $this->dispatch($job);
      QueueStatus::show('duplicate_content', $this->queue_id, 0);
    }

    public function searchIndex()
    {
      $job = (new JobSearchEngineIndex(
                  $this->url,
                  $this->current_id,
                  $this->queue_id,
                  $this->folder_name,
                  $this->time
              ));

      $this->dispatch($job);
      QueueStatus::show('search_engine_index', $this->queue_id, 0);
    }

    public function keywordFrequency()
    {
      $job = (new JobKeywordFrequency(
                  $this->url,
                  $this->current_id,
                  $this->queue_id,
                  $this->folder_name,
                  $this->time
              ));

      $this->dispatch($job);
      QueueStatus::show('keyword_frequency', $this->queue_id, 0);
    }

    public function socialInteraction()
    {
      $job = (new JobSocialInteraction(
                  $this->url,
                  $this->current_id,
                  $this->queue_id,
                  $this->folder_name,
                  $this->time
              ));

      $this->dispatch($job);
      QueueStatus::show('social_interaction', $this->queue_id, 0);
    }

    public function crawler()
    {
      $job = (new JobCrawler(
                  $this->url,
                  $this->current_id,
                  $this->queue_id,
                  $this->folder_name,
                  $this->time
              ));

      $this->dispatch($job);
      QueueStatus::show('crawler', $this->queue_id, 0);
    }

    /*
    * Majestic process still has problem with auth (majestic forbid robot access)
    */
    public function majestic()
    {
      $majestic = new Majestic($this->url);
      $majestic->login();
      //$majestic->getData();
    }

    public function testDate()
    {
      //dd(GF::validateDate('2015-01-21'));

    }
}
