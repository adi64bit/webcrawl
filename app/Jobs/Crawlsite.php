<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Library\Crawler;
use App\Helper\QueueStatus;
use \Storage;

class Crawlsite implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $url;

    protected $id;

    protected $queue_id;

    protected $result;

    protected $folder_name;

    protected $time;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url, $id, $folder_name, $time)
    {
      $this->url = $url;
      $this->id = $id;
      //$this->queue_id = $queue_id;
      $this->folder_name = $folder_name;
      $this->time = $time;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      //QueueStatus::show('crawler', $this->queue_id, 1, 0);
      Log::info("Request cycle without Queues started");
      $crawler = new Crawler($this->url, $this->folder_name, $this->time, 200, 5);
      $crawler->traverse();
      $crawler->getLinks();
      Log::info("test");
      //$this->result = $crawler->getLinks();

      //$result = Result::find($this->id);
      //$result->crawler = $this->result;
      //QueueStatus::show('crawler', $this->queue_id, 2, 1);
      //$result->save();
      //echo "<".$this->folder_name."> crawler complete\n";
    }
}
