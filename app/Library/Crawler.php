<?php

namespace App\Library;

use Goutte\Client as GoutteClient;
use Guzzle\Http\Exception\CurlException;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Carbon\Carbon;
use \Storage;
use GuzzleHttp\TransferStats;
use App\Library\Checker\Checker;

define('SITEMAP_VALIDATOR', 'https://www.xml-sitemaps.com/index.php?op=validate-xml-sitemap&go=1&sitemapurl=');

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

    public function __construct($baseUrl, $folder_name, $time, $maxPages = 100, $maxDepth = 5)
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

    protected function getScrapClient()
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

    public function traverse($url = null)
    {
        if ($url === null) {
            $url = $this->baseUrl;
            $this->links[$url] = array(
                'links_text' => array('BASE_URL'),
                'absolute_url' => $url,
                'frequency' => 1,
                'visited' => false,
                'external_link' => false,
                'original_urls' => array($url)
            );
        }

        $this->traverseSingle($url, $this->maxDepth);
        $this->getSitemap($url);
        $this->getRobots($url);
    }

    public function getLinks()
    {
        $this->info['code'] = 200;
        $this->info['url'] = $this->baseUrl;

        $content = json_encode($this->info, JSON_PRETTY_PRINT);
        $path = 'result/'.$this->folder_name.'/'.$this->time;
        Storage::makeDirectory($path, 2775, true);
        Storage::disk('local')->put($path.'/crawler.json', $content);
        return $path.'/crawler.json';
    }

    public function getRobots()
    {
      $url = rtrim($this->baseUrl, '/').'/robots.txt';
      $this->traverseOther($url, 'robots');
    }

    public function getSitemap()
    {
      /*
      * Sitemap common names worldwide
      * Source from http://dret.typepad.com/dretblog/2009/02/sitemap-names.html
      */
      $sitemap_name = array(
        0 => '/sitemap.xml', //25.07%
        1 => '/feeds/posts/default?orderby=updated',//8.38%
        2 => '/sitemap.xml.gz',//6.75%
        3 => '/sitemap_index.xml',//3.37%
        4 => '/s2/sitemaps/profiles-sitemap.xml',//1.91%
        5 => '/sitemap.php',//1.48%
        6 => '/sitemap_index.xml.gz',//1.24%
        7 => '/vb/sitemap_index.xml.gz',//1.05%
        8 => '/sitemapindex.xml',//0.66%
        9 => '/sitemap.gz'//0.45%
      );

      foreach ($sitemap_name as $key => $url) {
        $name[$key] = rtrim($this->baseUrl, '/').$url;
        $this->traverseOther($name[$key], 'sitemap');
      }
    }

    protected function validateSitemap($url)
    {
      $validator = SITEMAP_VALIDATOR.$url.'&submit=Validate';
      $client = new \GuzzleHttp\Client([
          'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36',
          ],
          'verify' => false
        ]);
      $res = $client->request('GET', $validator);
      $crawler = new DomCrawler((string)$res->getBody(true));
      $result = $crawler->filter('table tr')->eq(2)->filter('td')->last()->text();
      if($result == 'Yes')
      {
        return 200;
      }
      else
      {
        return 404;
      }
    }

    protected function traverseOther($url, $type)
    {
      try {
          $client = $this->getScrapClient();

          $crawler = $client->request('GET', $url);
          $statusCode = $client->getResponse()->getStatus();

          $hash = $this->getPathFromUrl($url);
          if($hash[0] != '/')
          {
            $hash = '/'.$hash;
          }

          /*
          * Check if sitemap is valid from other source
          */
          if($type == 'sitemap' && $statusCode == 200)
          {
            // $this->info[$type][$hash]['status_code'] = $this->validateSitemap($url);
            $this->info[$type][$hash]['status_code'] = $statusCode;
          }
          else
          {
            $this->info[$type][$hash]['status_code'] = $statusCode;
          }

          $this->info[$type][$hash]['filename'] = $hash;
      } catch (CurlException $e) {
          $this->info[$url]['status_code'] = '404';
          $this->info[$url]['error_code'] = $e->getCode();
          $this->info[$url]['error_message'] = $e->getMessage();
      } catch (\Exception $e) {
          $this->info[$url]['status_code'] = '404';
          $this->info[$url]['error_code'] = $e->getCode();
          $this->info[$url]['error_message'] = $e->getMessage();
      }
    }

    protected function traverseSingle($url, $depth)
    {
        try {
            if($this->current_page <= $this->maxPages)
            {
              $client = $this->getScrapClient();

              $crawler = $client->request('GET', $url);
              $statusCode = $client->getResponse()->getStatus();
              $hash = $this->getPathFromUrl($url);
              $this->info['webcrawler'][$hash]['status_code'] = $statusCode;

              if ($statusCode === 200) {
                  $content_type = $client->getResponse()->getHeader('Content-Type');

                  if (strpos($content_type, 'text/html') !== false) { //traverse children in case the response in HTML document only
                      $this->extractTitleInfo($crawler, $hash);
                      $childLinks = array();
                      //$this->info['webcrawler'][$hash]['page'] = $this->current_page;
                      if (isset($this->links[$hash]['external_link']) === true && $this->links[$hash]['external_link'] === false) {
                          $childLinks = $this->extractLinksInfo($crawler, $hash);
                      }

                      $this->links[$hash]['visited'] = true;
                      $this->traverseChildren($childLinks, $depth - 1);
                  }
              }
              $this->current_page++;
            }
        } catch (CurlException $e) {
            $this->info['webcrawler'][$url]['status_code'] = '404';
            $this->info['webcrawler'][$url]['error_code'] = $e->getCode();
            $this->info['webcrawler'][$url]['error_message'] = $e->getMessage();
        } catch (\Exception $e) {
            $this->info['webcrawler'][$url]['status_code'] = '404';
            $this->info['webcrawler'][$url]['error_code'] = $e->getCode();
            $this->info['webcrawler'][$url]['error_message'] = $e->getMessage();
        }
    }

    protected function traverseChildren($childLinks, $depth)
    {
        if ($depth === 0) {
            return;
        }

        foreach ($childLinks as $url => $info) {
          $hash = $this->getPathFromUrl($url);

          if (isset($this->links[$hash]) === false) {
              $this->links[$hash] = $info;
          } else {
              $this->links[$hash]['original_urls'] = isset($this->links[$hash]['original_urls']) ? array_merge($this->links[$hash]['original_urls'], $info['original_urls']) : $info['original_urls'];
              $this->links[$hash]['links_text'] = isset($this->links[$hash]['links_text']) ? array_merge($this->links[$hash]['links_text'], $info['links_text']) : $info['links_text'];
              if (isset($this->links[$hash]['visited']) === true && $this->links[$hash]['visited'] === true) {
                  $oldFrequency = isset($info['frequency']) ? $info['frequency'] : 0;
                  $this->links[$hash]['frequency'] = isset($this->links[$hash]['frequency']) ? $this->links[$hash]['frequency'] + $oldFrequency : 1;
              }
          }

          if (isset($this->links[$hash]['visited']) === false) {
              $this->links[$hash]['visited'] = false;
          }
          // && $this->links[$hash]['external_link'] === false
          if (empty($url) === false && $this->links[$hash]['visited'] === false && isset($this->links[$hash]['dont_visit']) === false && $info['external_link'] === false) {
              $this->traverseSingle($this->normalizeLink($childLinks[$url]['absolute_url']), $depth);
          }
        }
    }

    protected function extractLinksInfo(DomCrawler $crawler, $url)
    {
        $childLinks = array();
        $crawler->filter('a')->each(function (DomCrawler $node, $i) use (&$childLinks) {
            $node_text = trim($node->text());
            $node_url = $node->attr('href');
            $node_url_is_crawlable = $this->checkIfCrawlable($node_url);
            $hash = $this->normalizeLink($node_url);

            if (isset($this->links[$hash]) === false) {
                $childLinks[$hash]['original_urls'][$node_url] = $node_url;
                $childLinks[$hash]['links_text'][$node_text] = $node_text;
                if ($node_url_is_crawlable === true) {
                    // Ensure URL is formatted as absolute
                    if (preg_match("@^http(s)?@", $node_url) == false) {
                        if (strpos($node_url, '/') === 0) {
                            $parsed_url = parse_url($this->baseUrl);
                            $childLinks[$hash]['absolute_url'] = $parsed_url['scheme'] . '://' . $parsed_url['host'] . $node_url;
                        } else {
                            $base_host = rtrim($this->baseUrl, '/').'/';
                            $childLinks[$hash]['absolute_url'] = $base_host.$node_url;
                        }
                    } else {
                        $childLinks[$hash]['absolute_url'] = $node_url;
                    }

                    // Is this an external URL?
                    $childLinks[$hash]['external_link'] = $this->checkIfExternal($childLinks[$hash]['absolute_url']);
                    //$this->info['webcrawler'][$hash]['external_link'] = $this->checkIfExternal($childLinks[$hash]['absolute_url']);
                    // Additional metadata
                    $childLinks[$hash]['visited'] = false;
                    $childLinks[$hash]['frequency'] = isset($childLinks[$hash]['frequency']) ? $childLinks[$hash]['frequency'] + 1 : 1;
                } else {
                    $childLinks[$hash]['dont_visit'] = true;
                    $childLinks[$hash]['external_link'] = false;
                }
            }
        });

        // Avoid cyclic loops with pages that link to themselves
        if (isset($childLinks[$url]) === true) {
            $childLinks[$url]['visited'] = true;
        }

        return $childLinks;
    }

    protected function extractTitleInfo(DomCrawler $crawler, $url)
    {
        $checker = new Checker($crawler);

        $this->info['webcrawler'][$url]['title'] = $checker->getTitle($crawler, $url);
        $this->info['webcrawler'][$url]['description'] = $checker->getMetaDescription($crawler, $url);
        $this->info['webcrawler'][$url]['keywords'] = $checker->getMetaKeyword($crawler, $url);
        // $this->info['webcrawler'][$url]['meta'] = $checker->getMeta($crawler, $url);
        $this->info['webcrawler'][$url]['heading'] = $checker->getHeader($crawler, $url);
        $this->info['webcrawler'][$url]['hrefLang'] = $checker->getHrefLang($crawler, $url);
        $this->info['webcrawler'][$url]['imagesAlt'] = $checker->getAltImage($crawler, $url);
        $this->info['webcrawler'][$url]['openGraph'] = $checker->getOpenGraphMeta($crawler, $url);
    }

    protected function checkIfCrawlable($uri)
    {
        if (empty($uri) === true) {
            return false;
        }

        $stop_links = array(
            '@^javascript\:.*$@i',
            '@^#.*@',
            '@^mailto\:.*@i',
            '@^tel\:.*@i',
            '@^fax\:.*@i',
            '/\.(jpg|png|gif|jpeg|tif|pdf|cfm|JPG|PNG|GIF|JPEG|TIF|PDF|CFM)\b/'
        );

        foreach ($stop_links as $ptrn) {
            if (preg_match($ptrn, $uri) == true) {
                return false;
            }
        }

        return true;
    }

    protected function checkIfExternal($url)
    {
        $child_parsed = str_ireplace('www.', '', parse_url($url));
        $host_parsed = str_ireplace('www.', '', parse_url($this->baseUrl));

        /*
        * if child url only contain path (/contact-us.php || /contact-us.html || /contact-us || contact-us)
        * return false (this is internal url)
        */
        if(!isset($child_parsed['scheme']) && !isset($child_parsed['host']) && isset($child_parsed['path']))
        {
            return false;
        }
        else
        {
            /*
            * if child url host same as base url host retun false (this is internal url)
            */
            $extract = new \LayerShifter\TLDExtract\Extract();
            $child_domain = $extract->parse($url);
            $host_domain = $extract->parse($this->baseUrl);

            if($child_domain->getHostname() == $host_domain->getHostname())
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }

    protected function normalizeLink($uri)
    {
        return preg_replace('@#.*$@', '', $uri);
    }

    protected function getPathFromUrl($url)
    {
        /*$child_parsed = str_ireplace('www.', '', parse_url($url));
        $host_parsed = str_ireplace('www.', '', parse_url($this->baseUrl));

        if(!isset($child_parsed['scheme']) && !isset($child_parsed['host']) && isset($child_parsed['path']))
        {
           if(strpos($child_parsed['path'], '/') === 0)
           {
               return rtrim($child_parsed['path'], '/');
           }
           else
           {
               return '/'.rtrim($child_parsed['path'], '/');
           }
        }
        elseif(isset($child_parsed['scheme']) && isset($child_parsed['host']) && !isset($child_parsed['path']))
        {
            return $child_parsed['scheme'].'//'.$child_parsed['host'];
        }
        else
        {
            return rtrim($child_parsed['path'], '/');
        }*/

        if (strpos($url, $this->baseUrl) === 0 && $url !== $this->baseUrl) {
            return str_replace($this->baseUrl, '', $url);
        } else {
            return $url;
        }
    }
}
