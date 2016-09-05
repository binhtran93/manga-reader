<?php

namespace App\Services\Crawler;

use App\Services\Crawler\CrawlerAbstract;
use App\Services\Crawler\ICrawlerManga;
use Symfony\Component\DomCrawler\Crawler;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TruyenTranhDotNet
 *
 * @author Tran
 */
class TruyenTranhDotNet extends CrawlerAbstract implements ICrawlerManga {
    
    protected $domain = 'http://truyentranh.net/';
    
    protected $pageListUrl = 'danh-sach.tmoinhat.html/';
    
    protected $pagePattern = '?p=<%number%>';
    
    protected $lastPageNumber = 54;

    /**
     * Parent doc
     */
    public function getAllMangaUrl() {
        $mangaLinks = [];
        
        for ( $index = 0; $index < $this->lastPageNumber; $index++ ) {
            $pageNum = $index + 1;
            $uri = $this->_resolvePageLink($pageNum);
            $pageUrl = $this->domain . $this->pageListUrl . $uri;
            
            $mangaListContainer = $this->getMangaListContainer($pageUrl);
            
            $urls = $mangaListContainer->filter('tr')->each(function (Crawler $node, $index) {
                return [
                    'link' => $node->filter('td')->eq(1)->filter('a')->attr('href'),
                    'manga_name' => $node->filter('td')->eq(1)->filter('a')->attr('title'),
                    'domain' => $this->domain
                ];
            });
            
            foreach( $urls as $url ) {
                array_push($mangaLinks, $url);
            }
        }
        
        return $mangaLinks;
    }
    
    public function getMangaListContainer($pageUrl) {
        $response = $this->request->request('GET', $pageUrl);
        $body = $response->getBody()->getContents();
        $crawler = $this->createCrawler($body);
        
        return $crawler->filter('#loadlist');
        
    }
    
    public function getManga($url) {
        $body = $this->request->request('GET', $url)->getBody()->getContents();
        $mangaContainer = $this->getMangaContainer($body);
        
        $title = $this->getAuthor($mangaContainer);
        $tags = $this->getTags($mangaContainer);
        $status = $this->getMangaStatus($mangaContainer);
        dd($status);
//        $mangaStatus = $this->getMangaStatus($mangaContainer);
    }
    
    public function getChapterUrl() {
        
    }

    public function getChapterContainer() {
        
    }

    public function getMangaContainer($html) {
        $crawler = $this->createCrawler($html);
        return $crawler->filter('.manga-detail');
    }

    public function getAuthor($mangaContainer) {
        return $mangaContainer->filter('.title-manga')->text();
    }
    
    public function getTitle($mangaContainer) {
        return $mangaContainer->filter('.title-manga')->text();
    }
    
    public function getMangaStatus($mangaContainer) {
        $tagsEle = $mangaContainer->filter('.description-update')->filter('a');
        $status = $tagsEle->each(function (Crawler $node, $index) {
            $href = $node->attr('href');
            $components = parse_url($href);
            
            $path = $components['path'];
            $catName = explode('/', $path)[1];
            
            if ( $catName == 'trang-thai' ) {
                return $node->text();
            }
        });
        foreach ($status as $st) {
            if ( $st ) {
                $status = $st;
                break;
            } else {
                $status = '';
            }
        }
        return $status;
    }

    public function getTags($mangaContainer) {
        $tagsEle = $mangaContainer->filter('.CateName');
        $tags = $tagsEle->each(function (Crawler $node, $index) {
            $href = $node->attr('href');
            $components = parse_url($href);
            
            $path = $components['path'];
            $catName = explode('/', $path)[1];
            
            if ( $catName == 'the-loai' ) {
                return $node->attr('title');
            }
        });
        
        return $tags;
    }

    public function getTranslator($mangaContainer) {
        return '';
    }
    
    
//    protected _

}
