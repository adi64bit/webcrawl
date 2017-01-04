<?php
namespace App\Library;

//use \SEOstats\Services as SEOstats;
use \Storage;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use App\Helper\GlobalFunction as GF;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;

//define Search Engine
define('GOOGLE_PAGE_INDEX_URL', 'https://www.google.com/search?q=site:');
define('BING_PAGE_INDEX_URL', 'https://www.bing.com/search?q=site:');
define('SEARCH_ENGINE_INDEX', 'search_engine_index');

class SearchEngine
{
  protected $url;

  protected $result;

  protected $folder_name;

  protected $time;

    public function __construct($url, $folder_name, $time)
    {
      $this->url = GF::parseUrl($url);
      $this->folder_name = $folder_name;
      $this->result = array();
      $this->time = $time;
    }

    public function getAllResult()
    {
      $this->getGoogleIndex();
      $this->getBingIndex();

      $this->result['code'] = 200;
      $content = json_encode($this->result, JSON_PRETTY_PRINT);
      $path = 'result/'.$this->folder_name.'/'.$this->time;
      Storage::makeDirectory($path, 2775, true);
      Storage::disk('local')->put($path.'/search-engine-index.json', $content);
      return $path.'/search-engine-index.json';
    }

    public function getGoogleIndex()
    {
      $this->result[SEARCH_ENGINE_INDEX]['google']['result'] = 'error occured';
      $this->result[SEARCH_ENGINE_INDEX]['google']['url'] = GOOGLE_PAGE_INDEX_URL.$this->url;
      try{
        $jar = new \GuzzleHttp\Cookie\CookieJar();
        $client = new \GuzzleHttp\Client([
          'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36',
          ],
          'verify' => false
        ]);
        $res = $client->request('GET', GOOGLE_PAGE_INDEX_URL.$this->url, ['delay' => rand(5000,10000), 'cookies' => $jar]);
        $crawler = new DomCrawler((string)$res->getBody(true));
        if($crawler->filter('div#resultStats')->count() > 0)
        {
          $result = explode(' ', $crawler->filter('div#resultStats')->text());
          if($result[0] == 'About')
          {
            $this->result[SEARCH_ENGINE_INDEX]['google']['result'] = $result[1];
          }
          else
          {
            $this->result[SEARCH_ENGINE_INDEX]['google']['result'] = $result[0];
          }
          $this->result[SEARCH_ENGINE_INDEX]['google']['code'] = 200;
        }
        else
        {
          $this->result[SEARCH_ENGINE_INDEX]['google']['result'] = 'not indexed on Google';
          $this->result[SEARCH_ENGINE_INDEX]['google']['code'] = 404;
        }
      }
      catch(\GuzzleHttp\Exception\RequestException $e)
      {
        $this->result[SEARCH_ENGINE_INDEX]['google']['code'] = $e->getCode();
        $this->result[SEARCH_ENGINE_INDEX]['google']['message'] = $e->getMessage();
      }
    }

    public function getBingIndex()
    {
      $this->result[SEARCH_ENGINE_INDEX]['bing']['result'] = 'error occured';
      $this->result[SEARCH_ENGINE_INDEX]['bing']['url'] = BING_PAGE_INDEX_URL.$this->url;
      try{
        //$stack = new HandlerStack();
        //$stack->setHandler(new CurlHandler());
        //$stack->push(Middleware::tor());
        $jar = new \GuzzleHttp\Cookie\CookieJar();
        $client = new \GuzzleHttp\Client([
          'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36',
          ],
          'verify' => false
        ]);
        $res = $client->request('GET', BING_PAGE_INDEX_URL.$this->url, ['delay' => rand(5000,10000), 'cookies' => $jar]);
        $crawler = new DomCrawler((string)$res->getBody(true));
        if($crawler->filter('span.sb_count')->count() > 0)
        {
          $result = explode(' ', $crawler->filter('span.sb_count')->text());
          $this->result[SEARCH_ENGINE_INDEX]['bing']['result'] = $result[0];
          $this->result[SEARCH_ENGINE_INDEX]['bing']['code'] = 200;
        }
        else
        {
            $this->result[SEARCH_ENGINE_INDEX]['bing']['result'] = 'not indexed on Bing';
            $this->result[SEARCH_ENGINE_INDEX]['bing']['code'] = 404;
        }
      }
      catch(\GuzzleHttp\Exception\RequestException $e)
      {
        $this->result[SEARCH_ENGINE_INDEX]['bing']['code'] = $e->getCode();
        $this->result[SEARCH_ENGINE_INDEX]['bing']['message'] = $e->getMessage();
      }
    }
}

?>
