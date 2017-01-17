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
        $json = json_decode(file_get_contents('result/komodolines.com/2017-01-16_07\'09\'01/crawler.json'), true);
        $file['crawler'] = json_decode(file_get_contents('result/stikom-bali.ac.id/2017-01-17_05\'32\'28/crawler.json'), true);
        $count = 1;
        $result['image']['found'] = 0;
        $result['image']['missing_alt'] = 0;
        $result['image']['with_alt'] = 0;
      foreach ($file['crawler']['webcrawler'] as $key => $value) {

        //check title
        if(strlen($value['title'][0]) > 0 && strlen($value['title'][0]) < 10){
          if(isset($result['title']['short'])){
            $result['title']['short']++;
          } else {
            $result['title']['short'] = 1;
          }
        } elseif (strlen($value['title'][0]) >= 10 && strlen($value['title'][0]) <= 60) {
          if(isset($result['title']['good'])){
            $result['title']['good']++;
          } else {
            $result['title']['good'] = 1;
          }
        } elseif(strlen($value['title'][0]) > 60 ){
          if(isset($result['title']['long'])){
            $result['title']['long']++;
          } else {
            $result['title']['long'] = 1;
          }
        } else {
           if(isset($result['title']['null'])){
            $result['title']['null']++;
          } else {
            $result['title']['null'] = 1;
          }
        }
        //check meta description
        if(strlen($value['meta description'][0]) > 0 && strlen($value['meta description'][0]) < 100){
          if(isset($result['meta_description']['short'])){
            $result['meta_description']['short']++;
          } else {
            $result['meta_description']['short'] = 1;
          }
        } elseif (strlen($value['meta description'][0]) >= 100 && strlen($value['meta description'][0]) <= 160 ) {
          if(isset($result['meta_description']['good'])){
            $result['meta_description']['good']++;
          } else {
            $result['meta_description']['good'] = 1;
          }
        } elseif(strlen($value['meta description'][0]) > 160 ){
          if(isset($result['meta_description']['long'])){
            $result['meta_description']['long']++;
          } else {
            $result['meta_description']['long'] = 1;
          }
        } else {
           if(isset($result['meta_description']['null'])){
            $result['meta_description']['null']++;
          } else {
            $result['meta_description']['null'] = 1;
          }
        }
        //check status code
        if(isset($result['status_code'][$value['status_code']])){
          $result['status_code'][$value['status_code']]++;
        } else {
          $result['status_code'][$value['status_code']] = 1;
        }

        //check image 
        $result['image']['found'] = $result['image']['found'] + $value['image_count'];
        foreach ($value['image_with_alt'] as $altimg) {
          $result['image']['with_alt']++;
        }

        //check h1
        
      }
      $result['image']['missing_alt'] = $result['image']['found'] - $result['image']['with_alt'];

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
