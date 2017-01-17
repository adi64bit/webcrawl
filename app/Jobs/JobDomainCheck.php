<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use \Storage;
use App\Helper\QueueStatus;
use App\Library\DomainInfo;

class JobDomainCheck implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
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
        echo "[Domain Check] Start ".$this->folder_name."\n";
        QueueStatus::show('domain_info', $this->queue_id, 1, 0);
        $domainInfo = new DomainInfo($this->url, $this->folder_name, $this->time);
        //Get creation & expiration domain date
        $this->result = $domainInfo->DomainInfo();
        QueueStatus::show('domain_info', $this->queue_id, 2, 0);
        echo "[Domain Check] Complete ".$this->folder_name."  ".$this->result."\n";
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
      Storage::disk('local')->put($path.'/domain-info.json', $content);
      $file1 = $path.'/domain-info.json';
      QueueStatus::show('domain_info', $this->queue_id, 3, 0);
      echo "[Domain Check] failed ".$this->folder_name."  ".$this->result."\n";
    }
}
