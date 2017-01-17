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
          $table->integer('domain_id')->nullable();
          $table->string('pagespeed_mobile')->nullable();
          $table->string('pagespeed_desktop')->nullable();
          $table->string('crawler')->nullable();
          $table->string('domain_info')->nullable();
          $table->string('duplicate_content')->nullable();
          $table->string('search_engine_index')->nullable();
          $table->string('keyword_frequency')->nullable();
          $table->string('social_interaction')->nullable();
          $table->string('overall_status')->nullable();
          $table->string('is_complete')->nullable();
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
