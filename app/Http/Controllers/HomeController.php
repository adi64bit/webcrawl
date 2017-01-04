<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use \Storage;

use GuzzleHttp\TransferStats;
use App\Library\GooglePageSpeed;
use App\Library\DomainInfo;
use App\Library\SearchEngine;

class HomeController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function dashboard(){
        return view('pages.basePage');
    }

    public function addDomain(){

    }
    public function cekspeed(){
    	$time = Carbon::now()->format('Y-m-d_H\'i\'s');
      $url = 'http://www.handaragolfresort.com';
      $folder_name = 'www.handaragolfresort.com';
      $domainInfo = new DomainInfo($url, $folder_name, $time);
      $domainInfo->DomainInfo();
    
    	$pagespeed = new GooglePageSpeed($url, $folder_name, $time);
	    $pagespeed->mobile();
      $pagespeed->desktop();
      $searchengine = new SearchEngine($url, $folder_name, $time);
      $searchengine->getAllResult();
      //Storage::makeDirectory('domain test/', 0775, true);
      //Storage::disk('local')->put('domain test/domaininfo.txt', $result);
	    return view('pages.basePage');
    }
}
