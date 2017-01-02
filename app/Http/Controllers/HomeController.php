<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use \Storage;

use GuzzleHttp\TransferStats;
use App\Library\GooglePageSpeed;
use App\Library\DomainInfo;

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
    	$time = Carbon::now();
      	$domainInfo = new DomainInfo('www.komodolines.com', 'tes', $time);
      	//Get creation & expiration domain date
      	$domainInfo->getHostDateInfo();
      	//Get overall domain info;
     	//$domainInfo->getBuiltWithInfo();
    	$pagespeed = new GooglePageSpeed('www.komodolines.com', 'tes', $time);
	    $pagespeed->mobile();
	    return view('pages.basePage');
    }
}
