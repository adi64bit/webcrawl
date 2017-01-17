<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Library\SearchEngine;
use App\Helper\QueueStatus;
use \Storage;

class JobSearchEngine implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $current_id;
    protected $queue_id;
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
      echo "[search engine index] Start".$this->folder_name."\n";
      QueueStatus::show('search_engine_index', $this->queue_id, 1, 0);
      $index = new SearchEngine($this->url, $this->folder_name, $this->time);
      $index->getAllResult();
      QueueStatus::show('search_engine_index', $this->queue_id, 2, 0);
      echo "[search engine index] complete".$this->folder_name."\n";
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
      Storage::disk('local')->put($path.'/search-engine-index.json', $content);
      $file = $path.'/search-engine-index.json';
      QueueStatus::show('search_engine_index', $this->queue_id, 3, 0);
      echo "[search engine index] Failed".$this->folder_name."\n";
    }
}
