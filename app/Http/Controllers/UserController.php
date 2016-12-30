<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    /***************
    *   User Page   *
    ****************/
    public function getUser(){
        $page = 'User';
        $user_list = '';
        if ( Auth::check() && Auth::user()->haveRole('admin') ){
            $user_list = User::all();
            return view('pages.userPage', compact('page', 'user_list'));
        } else {
            return view('pages.userPage', compact('page', 'user_list'));
        }
    }

    /***************
    *   REGISTER   *
    ****************/
    public function getRegister(){
        if ( Auth::check() && Auth::user()->haveRole('admin') ){
            return view('modal.registerModal');
        } else {
            return false;
        }
        
    }
    public function postRegister(){
        //get data from role table
        $role_id = DB::table('roles')->select('id')->where('role_name', 'user')->first();
        //declare and insert data to Tabel User
        $user = new User();
        $user->name     = Input::get('name');
        $user->username = Input::get('username');
        $user->password = bcrypt(Input::get('password'));
        $user->role_id  = $role_id->id;
        $user->save();

        if ( $user->save() ){
            return redirect('/');
        } else {
            return redirect('/register');
        }
    }

    /***********
    *   LOGIN  *
    ************/
    public function getLogin(){
        return view('layout.login');
    }
    public function postLogin(Request $request){
        if(Auth::attempt([
            'username'  => $request->username,
            'password'  => $request->password
        ])){
            return redirect('/');
        } else {
            return 'salah';
        }
    }
}
