<?php

namespace App\Http\Controllers;
use App\User;
use App\Domain;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Carbon\Carbon;
use \Storage;

use GuzzleHttp\TransferStats;
use App\Jobs\Crawlsite;
use App\Library\GooglePageSpeed;
use App\Library\DomainInfo;
use App\Library\SearchEngine;
use App\Library\Crawler;

class HomeController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function dashboard(){
        /*$time = "2017-01-05_07'04'11";
        $timex = Carbon::now()->format('Y-m-d_H\'i\'s');
        $url = 'http://www.komodolines.com';
        $folder_name = 'www.komodolines.com';
        $crawler = array(
            'domain' => json_decode(Storage::get('/result/'.$folder_name.'/'.$time.'/domain-date.json')),
            'index' => json_decode(Storage::get('/result/'.$folder_name.'/'.$time.'/search-engine-index.json')),
            'PageSpeed' => array (
                  'desktop' => json_decode(Storage::get('/result/'.$folder_name.'/'.$time.'/pagespeed-desktop.json')),
                  'mobile' => json_decode(Storage::get('/result/'.$folder_name.'/'.$time.'/pagespeed-mobile.json'))
              ),
            'crawler' => json_decode(Storage::get('/result/'.$folder_name.'/'.$time.'/crawler.json'))
          );

        $job = new Crawlsite(
                  $url,
                  1,
                  $folder_name,
                  $timex
              );
         
      $this->dispatch($job);*/
      //QueueStatus::show('crawler', $this->queue_id, 0);
        return view('pages.basePage');
    }

    public function addDomain(){
      $page = 'User';
        $domain_list = '';
        if ( Auth::check() && Auth::user()->haveRole('admin') ){
            $domain_list = Domain::all();
            return view('pages.domainPage', compact('page', 'domain_list'));
        } else {
            return view('pages.domainPage', compact('page', 'domain_list'));
        }
    }
    
    public function cekspeed(){
    	/*$time = Carbon::now()->format('Y-m-d_H\'i\'s');
      $url = 'http://www.puripangan.co.id';
      $folder_name = 'www.puripangan.co.id';
      $crawler = new Crawler($url, $folder_name, $time);
      $crawler->traverse();
      $crawler->getLinks();
      $domainInfo = new DomainInfo($url, $folder_name, $time);
      $domainInfo->DomainInfo();
    	$pagespeed = new GooglePageSpeed($url, $folder_name, $time);
	    $pagespeed->mobile();
      $pagespeed->desktop();
      $searchengine = new SearchEngine($url, $folder_name, $time);
      $searchengine->getAllResult();*/

      //Storage::makeDirectory('domain test/', 0775, true);
      //Storage::disk('local')->put('domain test/domaininfo.txt', $result);
	    return view('pages.basePage');
    }
}
