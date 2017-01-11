<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QueueList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('queue_lists', function (Blueprint $table) {
          $table->increments('queue_id');
          $table->integer('domain_id');
          $table->string('pagespeed');
          $table->string('crawler');
          $table->string('domain_info');
          $table->string('duplicate_content');
          $table->string('search_engine_index');
          $table->string('keyword_frequency');
          $table->string('social_interaction');
          $table->string('overall_status');
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('queue_lists');
    }
}
