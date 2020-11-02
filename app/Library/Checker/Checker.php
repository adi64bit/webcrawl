<?php

namespace App\Library\Checker;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Checker{

    protected $OGmeta = array();
    
    function __construct() {
		
    }

    public function getHeader(DomCrawler $crawler, $url){
        $header = array(
            'h1' => $crawler->filter('h1')->each(function (DomCrawler $node, $i) {
                return $node->text();
            }),
            'h2' => $crawler->filter('h2')->each(function (DomCrawler $node, $i) {
                return $node->text();
            }),
            'h3' => $crawler->filter('h3')->each(function (DomCrawler $node, $i) {
                return $node->text();
            }),
            'h4' => $crawler->filter('h4')->each(function (DomCrawler $node, $i) {
                return $node->text();
            }),
            'h5' => $crawler->filter('h5')->each(function (DomCrawler $node, $i) {
                return $node->text();
            })
        );
        return $header;
    }

    public function getHrefLang(DomCrawler $crawler, $url){
        $hrefLang = array(
            'missing_attr' => array(),
            'empty_attr' => array(),
        );

        $alternateLinks = $crawler->filterXPath('//link[@rel="alternate"]');

        foreach ($alternateLinks as $alternateLink) {
            if (!$alternateLink->hasAttribute('hreflang')) {
                $hrefLang['missing_attr'][] = $alternateLink->getAttribute('href');
            } elseif (empty($alternateLink->getAttribute('hreflang'))) {
                $hrefLang['empty_attr'][] = $alternateLink->getAttribute('href');
            }
        }

        return $hrefLang;
    }

    public function getAltImage(DomCrawler $crawler, $url){
        $imageAlt = array(
            'image_found' => 0,
            'missing_alt' => array(
                'count' => 0,
                'url'   => array()
            ),
            'empty_alt' => array(
                'count' => 0,
                'url'   => array()
            ),
        );

        foreach ($crawler->filterXPath('//img') as $imageNode) {
            if (!$imageNode->hasAttribute('alt')) {
                $imageAlt['missing_alt']['url'][] = $imageNode->getAttribute('src');
                $imageAlt['missing_alt']['count']++;
            } elseif (empty(trim($imageNode->getAttribute('alt')))) {
                $imageAlt['empty_alt']['url'][] = $imageNode->getAttribute('src');
                $imageAlt['empty_alt']['count']++;
            }
            $imageAlt['image_found']++;
        }

        return $imageAlt;
    }

    public function getTitle(DomCrawler $crawler, $url){
        $numTitle = count($crawler->filterXPath('//head/title'));
        $title = array(
            'found' => $numTitle,
            'value' => $crawler->filterXPath('//head/title')->each(function (DomCrawler $node) {
                return $node->text();
            })
        );
        return $title;
    }

    public function getMetaDescription(DomCrawler $crawler, $url){
        $path = '//html/head/meta[@name="description"]';
        $xpath = $crawler->filterXPath($path);
        $metaDescription = array(
            'message' => '',
            'status' => false,
        );
        if (0 >= count($xpath)) {
            $metaDescription['message'] = 'Description tag is not available!';
            $metaDescription['status'] = false;
            return $metaDescription;
        }
        if (1 < count($xpath)) {
            $metaDescription['message'] = 'Too many Description tag! You should only have one!';
            $metaDescription['status'] = false;
            return $metaDescription;
        }
        $textLength = strlen($xpath->attr('content'));
        $message = 'Only ' .$textLength.' characters found.';
        if($textLength < 35){
            $metaDescription['message'] = 'Description tag must be at least 165 characters long. '.$message;
            $metaDescription['status'] = true;
            $metaDescription['content'] = $xpath->attr('content');
        } elseif ($textLength > 165) {
            $metaDescription['message'] = 'Description tag should not be longer than 165 characters. '.$message;
            $metaDescription['status'] = true;
            $metaDescription['content'] = $xpath->attr('content');
        } else {
            $metaDescription['message'] = 'Good';
            $metaDescription['status'] = true;
            $metaDescription['content'] = $xpath->attr('content');
        }
        return $metaDescription;
    }

    public function getMetaKeyword(DomCrawler $crawler, $url){
        $path = '//html/head/meta[@name="keywords"]';
        $xpath = $crawler->filterXPath($path);
        $metaKeyword = array(
            'message' => 'Good',
            'status' => true,
        );
        if (0 >= count($xpath)) {
            $metaKeyword['message'] = 'Keywords tag is not available!';
            $metaKeyword['status'] = false;
            return $metaKeyword;
        }
        if (1 < count($xpath)) {
            $metaKeyword['message'] = 'Too many Keywords tag! You should only have one!';
            $metaKeyword['status'] = false;
            return $metaKeyword;
        }
        if (!empty($xpath->attr('content'))) {
            $metaKeyword['content'] = $xpath->attr('content');
        }
        return $metaKeyword;
    }

    public function getMeta(DomCrawler $crawler, $url){
        $path = '//html/head/meta';
        $xpath = $crawler->filterXPath($path);
        $meta = $xpath->each(function (DomCrawler $node) {
            return array($node->attr('name'), $node->attr('content'));
        });
        return $meta;
    }

    public function getOpenGraphMeta(DomCrawler $crawler, $url){
        $this->OGmeta = [];
        $this->isFieldAvailable('og:locale', 'property', $crawler, $url);
        $this->isFieldAvailable('og:restrictions:content', 'property', $crawler, $url);
        $this->isFieldAvailable('og:url', 'property', $crawler, $url);
        $this->isFieldAvailable('og:description', 'property', $crawler, $url);
        $this->isFieldAvailable('og:site_name', 'property', $crawler, $url);
        $this->isFieldAvailable('og:type', 'property', $crawler, $url);
        $this->isFieldAvailable('og:title', 'property', $crawler, $url);
        return $this->OGmeta;
    }

    public function isFieldAvailable(string $fieldTagName, string $fieldType = 'name', DomCrawler $crawler, $url){
        $path = '//html/head/meta[@'.$fieldType.'="'.$fieldTagName.'"]';
        $xpath = $crawler->filterXPath($path);

        $this->OGmeta[$fieldTagName] = array(
            'message' => 'Tag found',
            'status' => true,
        );

        if (0 >= count($xpath)) {
            $this->OGmeta[$fieldTagName]['message'] = $fieldTagName.' tag is not available!';
            $this->OGmeta[$fieldTagName]['status'] = false;
            return;
        }
        if (1 < count($xpath)) {
            $this->OGmeta[$fieldTagName]['message'] = 'Too many '.$fieldTagName.' tag! You should only have one!';
            $this->OGmeta[$fieldTagName]['status'] = false;
            return;
        }

        if (!empty($xpath->attr('content'))) {
            $this->OGmeta[$fieldTagName]['content'] = $xpath->attr('content');
        }

        if($fieldTagName == 'og:description'){
            if (!empty($xpath->attr('content'))) {
                $textLength = strlen($xpath->attr('content'));
                $message = 'Only ' .$textLength.' characters found.';
                if($textLength < 35){
                    $this->OGmeta[$fieldTagName]['message'] = $fieldTagName.' tag must be at least 165 characters long. '.$message;
                    $this->OGmeta[$fieldTagName]['status'] = true;
                    $this->OGmeta[$fieldTagName]['content'] = $xpath->attr('content');
                } elseif ($textLength > 165) {
                    $this->OGmeta[$fieldTagName]['message'] = $fieldTagName.' tag should not be longer than 165 characters. '.$message;
                    $this->OGmeta[$fieldTagName]['status'] = true;
                    $this->OGmeta[$fieldTagName]['content'] = $xpath->attr('content');
                } else {
                    $this->OGmeta[$fieldTagName]['message'] = 'Good';
                    $this->OGmeta[$fieldTagName]['status'] = true;
                    $this->OGmeta[$fieldTagName]['content'] = $xpath->attr('content');
                }
            }
        }
    }
}
