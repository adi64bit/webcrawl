<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //create schema users
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('role_id')->nullable();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        //create schema roles
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('role_name');
        });

        //create schema foreign key
        Schema::table('users', function(Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
        });

        //insert 2 role data admin and user
        DB::table('roles')->insert(['role_name' => 'admin']);
        DB::table('roles')->insert(['role_name' => 'user']);

        //insert admin default USER : admin PASS : admin
        $role_id = DB::table('roles')->select('id')->where('role_name', 'admin')->first();
        DB::table('users')->insert([
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => bcrypt('admin'),
                'role_id'   =>  $role_id->id
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
}
