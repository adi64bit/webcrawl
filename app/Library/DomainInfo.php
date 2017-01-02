<?php
namespace App\Library;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Carbon\Carbon;
use \Storage;
use App\Helper\GlobalFunction as GF;

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

  public function getHostDateInfo()
  {
    $url = $this->parseUrl($this->url); //google.com
    $domain = explode('.', $url);// [0]google [1]com
    $method = '';
    $this->date_info[DOMAIN_DATE]['creation_date'] = 'not found';
    $this->date_info[DOMAIN_DATE]['expiration_date'] = 'not found';

    /*
    * Verisign whois api
    * Only support domain .com, .cc, .net, .edu, .name, .tv
    */
    /*$v_supported = array('com', 'cc', 'net', 'edu', 'name', 'tv');

    if(!in_array($domain[1], $v_supported))
    {
      $method = 'verisign';
      $endpoint = VERGISIGN.$domain[0].'&tld='.$domain[1].'&type=domain';
    }
    else
    {
      $method = 'whois';
      $endpoint = WHOIS.$url;
    }*/
    $method = 'whois';
    $endpoint = WHOIS.$url;

    $this->date_info[DOMAIN_DATE]['url'] = $endpoint;

    $client = new \GuzzleHttp\Client();
    if($method == 'verisign')
    {
      try
      {
        $res = $client->request('GET', $endpoint, ['verify' => false]);
        $message = json_decode( (string)$res->getBody() )->message;
        $pattern = '/\s([0-9]{2}-[a-z]{3}-[0-9]{4})/';
        preg_match_all($pattern, $message, $match);
        if(!empty($match[0]))
        {
          $creation_date = date('Y-m-d', strtotime( (string)$match[0][1]) );
          $expiration_date = date('Y-m-d', strtotime( (string)$match[0][2]) );
          $this->date_info[DOMAIN_DATE]['creation_date'] = $creation_date;
          $this->date_info[DOMAIN_DATE]['expiration_date'] = $expiration_date;
        }
        $this->date_info[DOMAIN_DATE]['code'] = 200;
      }
      catch(\GuzzleHttp\Exception\RequestException $e)
      {
        $this->date_info[DOMAIN_DATE]['code'] = $e->getCode();
        $this->date_info[DOMAIN_DATE]['message'] = $e->getMessage();
      }
    }
    else
    {
      try
      {
        $res = $client->request('GET', $endpoint);
        $crawler = (string)$res->getBody(true);
        $pattern = '/([0-9]{4}-[0-9]{2}-[0-9]{2})|([0-9]{2}-[a-zA-Z]{3}-[0-9]{4})/';

        preg_match_all($pattern, $crawler, $match);
        if(!empty($match[0]))
        {
          $match_count = count($match[0]);
          unset($match[0][$match_count-1]);
          $new_match_count = $match_count - 1;
          if($new_match_count > 1 && GF::validateDate($match[0][0]))
          {
            $this->date_info[DOMAIN_DATE]['expiration_date'] = $match[0][0];
            $this->date_info[DOMAIN_DATE]['creation_date'] = $match[0][1];
          }
          elseif($new_match_count > 1)
          {
            $this->date_info[DOMAIN_DATE]['expiration_date'] = date('Y-m-d', strtotime($match[0][2]));
            $this->date_info[DOMAIN_DATE]['creation_date'] = date('Y-m-d', strtotime($match[0][0]));
          }
        }
        $this->date_info[DOMAIN_DATE]['code'] = 200;
      }
      catch(\GuzzleHttp\Exception\RequestException $e)
      {
        $this->date_info[DOMAIN_DATE]['code'] = $e->getCode();
        $this->date_info[DOMAIN_DATE]['message'] = $e->getMessage();
      }
    }

    $content = json_encode($this->date_info[DOMAIN_DATE], JSON_PRETTY_PRINT);
    $path = 'result/'.$this->folder_name.'/'.$this->time;
    Storage::makeDirectory($path, 2775, true);
    Storage::disk('local')->put($path.'/domain-date.json', $content);
    return $path.'/domain-date.json';
  }

  public function getBuiltWithInfo()
  {
    $this->info[DOMAIN_INFO]['data'] = 'not found';
    $url = $this->parseUrl($this->url);
    $endpoint = BUILT_WITH.$url;
    $this->info[DOMAIN_INFO]['url'] = $endpoint;

    try{
      $client = new \GuzzleHttp\Client();
      $res = $client->request('GET', $endpoint);
      $crawler = new DomCrawler((string)$res->getBody(true));
      $this->extractHosting($crawler, $url);
      $this->parseInfo();
      $this->info[DOMAIN_INFO]['code'] = 200;
    }
    catch(\GuzzleHttp\Exception\RequestException $e)
    {
      $this->info[DOMAIN_INFO]['code'] = $e->getCode();
      $this->info[DOMAIN_INFO]['message'] = $e->getMessage();
    }

    $content = json_encode($this->info[DOMAIN_INFO], JSON_PRETTY_PRINT);
    $path = 'result/'.$this->folder_name.'/'.$this->time;
    Storage::makeDirectory($path, 2775, true);
    Storage::disk('local')->put($path.'/domain-info.json', $content);
    return $path.'/domain-info.json';
  }

  //Extract data from Builtwith
  public function extractHosting($crawler, $url)
  {
    $crawler->filter('.span8 div')->each(function (DomCrawler $node, $i) use ($url) {
      $filter = 'a:last-child';
      $title = '';
      if($node->attr('class') == 'titleBox')
      {
        $filter = 'li span';
        $title = 'cat: ';
      }
      return $this->tmp[$i] = $title.$node->filter($filter)->text();
    });
  }

  //Parse data from Builtwith result
  public function parseInfo()
  {
    $category = '';
    $tmp = array();
    foreach ($this->tmp as $key => $value)
    {
      if(strpos($value, 'cat: ') !== false)
      {
        $category = str_replace('cat: ', '', $value);
      }
      else
      {
        $tmp[$category][] = $value;
      }
    }
    $this->info[DOMAIN_INFO]['data'] = $tmp;
  }
}
?>
