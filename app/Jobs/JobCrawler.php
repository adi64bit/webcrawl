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

class JobCrawler implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $url;

    protected $id;

    protected $queue_id;

    //protected $result;

    protected $folder_name;

    protected $time;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url, $id, $queue_id, $folder_name, $time)
    {
      $this->url = $url;
      $this->id = $id;
      $this->queue_id = $queue_id;
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
      echo "[Crawler] \"Start\" ".$this->folder_name."\n";
      QueueStatus::show('crawler', $this->queue_id, 1, 0);
      $crawler = new Crawler($this->url, $this->folder_name, $this->time, 10, 2);
      $crawler->traverse();
      $crawler->getLinks();
      QueueStatus::show('crawler', $this->queue_id, 2, 1);
      echo "[Crawler] \"Complete\" ".$this->folder_name."\n";
    }

    public function failed()
    {
      $error_result = array(
        'code'    =>  404,
        'url'     => $this->url,
        'error_message' => 'File not found'
      );

      $content = json_encode($error_result, JSON_PRETTY_PRINT);
      $path = 'result/'.$this->folder_name.'/'.$this->time;
      Storage::makeDirectory($path, 2775, true);
      Storage::disk('local')->put($path.'/crawler.json', $content);
      $file = $path.'/crawler.json';

      QueueStatus::show('crawler', $this->queue_id, 3, 1);
      echo "[Crawler] \"Failed\" ".$this->folder_name."\n";
    }
}
