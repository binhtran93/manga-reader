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
     * get all manga in url
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
    
    /**
     * Get manga list container element
     * @param type $pageUrl
     * @return type
     */
    public function getMangaListContainer($pageUrl) {
        $response = $this->request->request('GET', $pageUrl);
        $body = $response->getBody()->getContents();
        $crawler = $this->createCrawler($body);
        
        return $crawler->filter('#loadlist');
        
    }
    
    /**
     * Get all information of manga like name, author, chapter list...
     * @param type $url
     */
    public function getManga($url) {
        $body = $this->request->request('GET', $url)->getBody()->getContents();
        $mangaContainer = $this->getMangaContainer($body);
        $chapterContainer = $this->getChapterListContainer($body);
        
        $title = $this->getAuthor($mangaContainer);
        $tags = $this->getTags($mangaContainer);
        $status = $this->getMangaStatus($mangaContainer);
        $author = $this->getAuthor($mangaContainer);
        $chapters = $this->getChapters($chapterContainer);
        dd($chapters);
        
        return [
            'title' =>$title,
            'tags' => $tags,
            'status' => $status,
            'author' => $author,
            'chapters' => $chapters
        ];
    }
    
    /**
     * Get list chapter container
     * @param type $html
     * @return type
     */
    public function getChapterListContainer($html) {
        $crawler = $this->createCrawler($html);
        return $crawler->filter('.mCustomScrollbar');
    }
    
    /**
     * Get manga container element
     * @param type $html
     * @return type
     */
    public function getMangaContainer($html) {
        $crawler = $this->createCrawler($html);
        return $crawler->filter('.manga-detail');
    }

    /**
     * Get author of manga
     * @param type $mangaContainer
     * @return type
     */
    public function getAuthor($mangaContainer) {
        return $mangaContainer->filter('.title-manga')->text();
    }
    
    /**
     * Get manga title
     * @param type $mangaContainer
     * @return type
     */
    public function getTitle($mangaContainer) {
        return $mangaContainer->filter('.title-manga')->text();
    }
    
    /**
     * Get manga status: continue or full
     * @param type $mangaContainer
     * @return type
     */
    public function getMangaStatus($mangaContainer) {
        $statusEle = $mangaContainer->filter('.description-update')->filter('a');
        $status = $statusEle->each(function (Crawler $node, $index) {
            $href = $node->attr('href');
            $components = parse_url($href);
            
            $path = $components['path'];
            $catName = explode('/', $path)[1];
            
            if ( $catName == 'trang-thai' ) {
                if ( $node->text() == 'Còn tiếp' ) {
                    return 'continue';
                } else {
                    return 'full';
                }
            }
        });
        
        $status = array_filter($status);
        return array_shift($status);
    }

    /**
     * Get all tags of manga in array
     * @param type $mangaContainer
     * @return type
     */
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
        
        
        return array_filter ($tags);
    }
    
    /**
     * Get translator of manga
     * @param type $mangaContainer
     * @return string
     */
    public function getTranslator($mangaContainer) {
        $authorEle = $mangaContainer->filter('.description-update')->filter('a');
        $author = $authorEle->each(function (Crawler $node, $index) {
            $href = $node->attr('href');
            $components = parse_url($href);
            
            $path = $components['path'];
            $catName = explode('/', $path)[1];
            
            if ( $catName == 'tac-gia' ) {
                return $node->text();
            }
        });
        
        $author = array_filter($status);
        return array_shift($author);
    }
    
    /**
     * Get all chapter of manga
     */
    public function getChapters($chapterListContainer) {
        $chapters = [];
        
        $chapterListContainer->filter('p > a')->each(function(Crawler $node, $index) use (&$chapters, &$test) {    
            $chapUrl = $node->attr('href');
            $pattern = '/(chap|chjap)\-\d+(\.\d+)?/i';
            preg_match($pattern, $chapUrl, $matches);
            
            $string = array_key_exists( 0, $matches ) ? $matches[0] : '';
            $arrayTmp = explode('-', $string);
            $chapNumber = $arrayTmp[1] + 0;
            
            $body = $this->request->request('GET', $chapUrl)->getBody()->getContents();
            $chaptersContainer = $this->createCrawler($body)->filter('.paddfixboth-mobile');
            
            $chapters[$chapNumber] = $this->getChaptersUrl($chaptersContainer);
        });
        
        return $chapters;
    }
    
    /**
     * 
     * @param type $chapterContainer
     */
    public function getChaptersUrl($chapterContainer) {
        $chapterMedias = [];
        $chapterContainer->filter('img')->each(function(Crawler $node, $index) use (&$chapterMedias) {
            array_push($chapterMedias, $node->attr('src'));
        });
        
        return $chapterMedias;
    }
    

}
