<?php
namespace App\Library;

use Goutte\Client as GoutteClient;
use Guzzle\Http\Exception\CurlException;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Carbon\Carbon;
use \Storage;
use GuzzleHttp\TransferStats;

// site to validate sitemap
define('SITEMAP_VALIDATOR', 'https://www.xml-sitemaps.com/index.php?op=validate-xml-sitemap&go=1&sitemapurl=');

/**
* 
*/
class Crawler
{
	  protected $baseUrl;

    protected $maxDepth;

    protected $links;

    protected $maxPages;

    protected $info;

    protected $folder_name;

    protected $time;

    protected $current_page;

    public function __construct($baseUrl, $folder_name, $time)
    {
        $this->baseUrl = $baseUrl;
        $this->maxDepth = $maxDepth;
        $this->links = array();
        $this->maxPages = $maxPages + 1;
        $this->info = array();
        $this->folder_name = $folder_name;
        $this->time = $time;
        $this->current_page = 1;
    }

    protected function scrappingClient()
    {
        $client = new GoutteClient();
        $client->followRedirects();

        $guzzleClient = new \GuzzleHttp\Client(array(
            'curl' => array(
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
            ),
        ));
        $client->setClient($guzzleClient);

        return $client;
    }
}
