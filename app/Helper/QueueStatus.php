<?php
  namespace App\Helper;

  use App\QueueList;

  class QueueStatus{

    public static function show($process, $queue_id, $status, $all_complete = -1)
    {
      $status_text = '';
      $isCompleted = false;

      switch($status)
      {
        case 1:
          $status_text = 'on-progress';
          break;
        case 2:
          $status_text = 'completed';
          $isCompleted = true;
          break;
        case 3:
          $status_text = 'failed';
          break;
        default:
          $status_text = 'on-queue';
      }

      $queue = QueueList::find($queue_id);

      switch($process)
      {
        case 'pagespeed_desktop':
          $queue->pagespeed_desktop = $status_text;
          break;
        case 'pagespeed_mobile':
          $queue->pagespeed_mobile = $status_text;
          break;
        case 'crawler':
          $queue->crawler = $status_text;
          break;
        case 'domain_info':
          $queue->domain_info = $status_text;
          break;
        case 'domain_date':
          $queue->domain_date = $status_text;
          break;
        case 'duplicate_content':
          $queue->duplicate_content = $status_text;
          break;
        case 'search_engine_index':
          $queue->search_engine_index = $status_text;
          break;
        case 'keyword_frequency':
          $queue->keyword_frequency = $status_text;
          break;
        case 'social_interaction':
          $queue->social_interaction = $status_text;
      }

      if($isCompleted == true && $queue->overall_status < 9)
      {
        $queue->overall_status = $queue->overall_status + 1;
      }

      if($queue->overall_status == 9)
      {
        $queue->is_complete = 1;
      }
      else
      {
        $queue->is_complete = $all_complete;
      }

      $queue->save();
    }

  }
?>
