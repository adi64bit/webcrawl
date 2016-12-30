<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function haveRole($role_name){

        $role = DB::table( 'roles' )->select( 'role_name' )->where( 'id', $this->role_id )->first();
        //dd($this->role->role_name);
        //dd($role, $this->role->role_name == $role_name);
        if($this->role->role_name == $role_name){
            return true;
        }
        return false;
    }
}
