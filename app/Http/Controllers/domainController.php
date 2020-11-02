<?php

namespace App\Http\Controllers;
use App\User;
use App\Domain;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use \Storage;
use \PhpInsights\InsightsCaller;
use Illuminate\Http\Request;

class domainController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $caller = new InsightsCaller('AIzaSyAcKhHJfOIT5NQYYU0ACC5I_yqu2GdkCXE', 'en');
        //Mobile
        $mobile = $caller->getResponse('https://shopthepaws.com', InsightsCaller::STRATEGY_MOBILE);
        $mobileResult = $mobile->getRawResult();
        dd($mobileResult);
        $domain_list = '';
        if ( Auth::check() && Auth::user()->haveRole('admin') ){
            $domain_list = Domain::all();
            return view('pages.domainPage', compact('page', 'domain_list', 'mobileResult'));
        } else {
            return view('pages.domainPage', compact('page', 'domain_list'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getDomain = Domain::where('domain_id', $id)->get();
        if ( Auth::check() && Auth::user()->haveRole('admin') && $getDomain ){
            $domain = $getDomain[0];
            return view('pages.domain.domainDetail', compact('domain'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
