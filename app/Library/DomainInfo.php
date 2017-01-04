<?php
namespace App\Library;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Carbon\Carbon;
use \Storage;
use App\Helper\GlobalFunction as GF;
use App\Library\DetectCMS\DetectCMS;

define('DOMAIN_DATE', 'domain_date');
define('DOMAIN_INFO', 'domain_info');

define('BUILT_WITH', 'http://www.builtwith.com/');
define('VERGISIGN', 'https://registrar.verisign-grs.com/webwhois-ui/rest/whois?q=');
define('WHOIS', 'https://who.is/whois/');


class DomainInfo{

  protected $url;

  protected $info;

  protected $date_info;

  protected $tmp;

  protected $folder_name;

  protected $time;

  public function __construct($url, $folder_name, $time)
  {
    $this->url = $url;
    $this->info = array();
    $this->date_info = array();
    $this->tmp = array();
    $this->folder_name = $folder_name;
    $this->time = $time;
  }

  public function parseUrl($url)
  {
    $domain = str_ireplace('www.', '', parse_url($url, PHP_URL_HOST));
    return $domain;
  }

  public function DomainInfo(){
    $url = $this->parseUrl($this->url);
    $method = 'whois';
    $endpoint = WHOIS.$url;

    //check CMS
    $cms = new DetectCMS('http://'.$url);
    if($cms->getResult()) {
        $this->date_info[DOMAIN_DATE]['website_cms'] = $cms->getResult();
    } else {
        $this->date_info[DOMAIN_DATE]['website_cms'] = 'CMS couldn\'t be detected';
    } 
    $this->date_info[DOMAIN_DATE]['creation_date'] = 'not found';
    $this->date_info[DOMAIN_DATE]['update_date'] = 'not found';
    $this->date_info[DOMAIN_DATE]['expiration_date'] = 'not found';
    $this->date_info[DOMAIN_DATE]['url'] = $endpoint;
    try {
      $client = new \GuzzleHttp\Client();
      $res = $client->request('GET', $endpoint, ['verify' => false]);
      $dom = (string)$res->getBody(true);
      $pattern = '/([0-9]{4}-[0-9]{2}-[0-9]{2})|([0-9]{2}-[a-zA-Z]{3}-[0-9]{4})/';
      preg_match_all($pattern, $dom, $match);
      if(!empty($match[0])){
        $this->date_info[DOMAIN_DATE]['creation_date'] = $match[0][1];
        $this->date_info[DOMAIN_DATE]['update_date'] = $match[0][2];
        $this->date_info[DOMAIN_DATE]['expiration_date'] = $match[0][0];
      }
      $this->date_info[DOMAIN_DATE]['code'] = 200;
    } catch(\GuzzleHttp\Exception\RequestException $e){
      $this->date_info[DOMAIN_DATE]['code'] = $e->getCode();
      $this->date_info[DOMAIN_DATE]['message'] = $e->getMessage();
    }
    $content = json_encode($this->date_info[DOMAIN_DATE], JSON_PRETTY_PRINT);
    $path = 'result/'.$this->folder_name.'/'.$this->time;
    Storage::makeDirectory($path, 0775, true);
    Storage::disk('local')->put($path.'/domain-date.json', $content);
    return $path.'/domain-date.json';
  }
}

?>
