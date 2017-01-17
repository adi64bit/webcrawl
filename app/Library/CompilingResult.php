<?php

namespace App\Library;

use \Storage;

class CompilingResult {
	protected $url;
	protected $folder_name;
	protected $queue_id;
	protected $id;
	protected $time;
	protected $file;

	public function __construct($url,$id, $queue_id, $folder_name, $time)
    {
		$this->url = $url;
		$this->folder_name = $folder_name;
		$this->queue_id = $queue_id;
		$this->id = $id;
		$this->time = $time;
    }

    public function getResult(){
    	$linkerror = 0;
    	// get all file 
    
    	$this->file['domain-date'] = json_decode(file_get_contents('result/'.$this->folder_name.'/'.$this->time.'/domain-date.json'), true);

    	$this->file['search-engine-index'] = json_decode(file_get_contents('result/'.$this->folder_name.'/'.$this->time.'/search-engine-index.json'), true);

    	$this->file['pagespeed-mobile'] = json_decode(file_get_contents('result/'.$this->folder_name.'/'.$this->time.'/pagespeed-mobile.json'), true);

    	$this->file['pagespeed-desktop'] = json_decode(file_get_contents('result/'.$this->folder_name.'/'.$this->time.'/pagespeed-desktop.json'), true);

    	$this->file['crawler'] = json_decode(file_get_contents('result/'.$this->folder_name.'/'.$this->time.'/crawler.json'), true);

    	foreach ($this->file['crawler']['webcrawler'] as $key => $value) {
    		$link[$value->status_code]++;
    	}
    }
}
