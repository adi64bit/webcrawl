<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Library\GooglePageSpeed;
use \Storage;
use App\Helper\QueueStatus;

class JobPageSpeed implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    protected $url;
    protected $strategy;
    protected $current_id;
    protected $queue_id;
    protected $folder_name;
    protected $time;
    protected $result;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url, $strategy, $current_id, $queue_id, $folder_name, $time)
    {
        $this->url = $url;
        $this->strategy = $strategy;
        $this->current_id = $current_id;
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
        echo "[Page Speed ".$this->strategy."] Start ".$this->folder_name."\n";
        QueueStatus::show('pagespeed_'.$this->strategy, $this->queue_id, 1, 0);
        $pagespeed = new GooglePageSpeed($this->url, $this->folder_name, $this->time, $this->strategy);
        if($this->strategy == 'desktop')
        {
            $this->result = $pagespeed->desktop();
            QueueStatus::show('pagespeed_desktop', $this->queue_id, 2, 0);
        }
        elseif($this->strategy == 'mobile')
        {
            $this->result = $pagespeed->mobile();
            QueueStatus::show('pagespeed_mobile', $this->queue_id, 2, 0);
        }
        echo "[Page Speed ".$this->strategy."] Complete ".$this->folder_name."".$this->result."\n";
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
      if($this->strategy == 'desktop')
      {
        Storage::makeDirectory($path, 2775, true);
        Storage::disk('local')->put($path.'/pagespeed-desktop.json', $content);
        $file = $path.'/pagespeed-desktop.json';
        QueueStatus::show('pagespeed_desktop', $this->queue_id, 3, 0);
      }
      else
      {
        Storage::makeDirectory($path, 2775, true);
        Storage::disk('local')->put($path.'/pagespeed-mobile.json', $content);
        $file1 = $path.'/pagespeed-mobile.json';
        QueueStatus::show('pagespeed_mobile', $this->queue_id, 3, 0);
      }
      echo "[Page Speed ".$this->strategy."] Complete ".$this->folder_name."".$this->result."\n";
    }
}
