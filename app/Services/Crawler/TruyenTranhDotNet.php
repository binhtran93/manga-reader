<?php

namespace App\Services\Crawler;

use App\Services\Crawler\CrawlerAbstract;
use App\Services\Crawler\ICrawlerManga;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Log;
use App\Services\Crawler\ChapterException;

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
//        $url = 'http://truyentranh.net/being-boyfriend-and-girlfriend';
        $body = $this->request->request('GET', $url)->getBody()->getContents();
        $mangaContainer = $this->getMangaContainer($body);
        $chapterContainer = $this->getChapterListContainer($body);
        
        $title = $this->getTitle($mangaContainer);
        $tags = $this->getTags($mangaContainer);
        $status = $this->getMangaStatus($mangaContainer);
        $authors = $this->getAuthor($mangaContainer);
        $chapters = $this->getChapters($chapterContainer);
        $thumbnail = $this->getThumbnail($mangaContainer);
        $description = $this->getDescription($mangaContainer);
        
        return [
            'title' =>$title,
            'tags' => $tags,
            'status' => $status,
            'authors' => $authors,
            'chapters' => $chapters,
            'thumbnail' => $thumbnail,
            'description' => $description
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
                if ( strcasecmp($node->text(), 'Còn tiếp' ) == 0 ) {
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
     * Get author of manga
     * @param type $mangaContainer
     * @return string
     */
    public function getAuthor($mangaContainer) {
        $authorEle = $mangaContainer->filter('.description-update')->filter('a');
        $authors = [];
        $authorEle->each(function (Crawler $node, $index) use (&$authors) {
            $href = $node->attr('href');
            $components = parse_url($href);
            
            $path = $components['path'];
            $catName = explode('/', $path)[1];
            
            if ( $catName == 'tac-gia' ) {
                $authors[] = $node->text();
            }
        });
        
        return $authors;
    }
    
    /**
     * Get all chapter of manga
     */
    public function getChapters($chapterListContainer) {
        $chapters = [];
        
        $chapterListContainer->filter('p > a')->each(function(Crawler $node, $index) use (&$chapters) {    
            $chapUrl = $node->attr('href');
            $ignoreList = $this->getIgnoreChapterList();
            
            if ( in_array($chapUrl, $ignoreList) ) return false;
            
            $pattern = '/(chap|chjap|cxhap|chao)\-+\d+(\.\d+)?[a-z]*/i';  
            preg_match($pattern, $chapUrl, $matches);
            
            $string = array_key_exists( 0, $matches ) ? $matches[0] : '';
            $arrayTmp = explode('-', $string);
            
            if ( !isset($arrayTmp[1]) ) {
                file_put_contents(storage_path('logs/chapter-log.txt'), "'".$chapUrl . "',\r\n", FILE_APPEND);
                return false;
            }
            
            $chapNumber = $arrayTmp[1];
            
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

    /**
     * Get description of manga
     * @param type $mangaCOntainer
     * @return string
     */
    public function getDescription($mangaCOntainer) {
        return '';
    }

    /**
     * Get thumbnail of manga
     * @param type $mangaCOntainer
     * @return string
     */
    public function getThumbnail($mangaCOntainer) {
        return '';
    }
    
    /**
     * get traslator of manga
     * @param type $mangaContainer
     */
    public function getTranslator($mangaContainer) {
        return '';
    }
    
    public function getIgnoreChapterList() {
        return [
            'http://truyentranh.net/world-trigger/world-trigger-border-briefing-file',
            'http://truyentranh.net/dau-pha-thuong-khung/Dau-Pha-Thuong-Khung-Ngoai-truyen-001',
            'http://truyentranh.net/dau-pha-thuong-khung/Dau-Pha-Thuong-Khung-Ngoai-truyen-002',
            'http://truyentranh.net/to-love-ru-darkness/To-Love-Ru-Darkness-Bangai-Hen-Spring-2011',
            'http://truyentranh.net/to-love-ru-darkness/To-Love-Ru-Darkness-Venus-part-001',
            'http://truyentranh.net/to-love-ru-darkness/To-Love-Ru-Darkness-Venus-part-002',
            'http://truyentranh.net/to-love-ru-darkness/To-Love-Ru-Darkness-Venus-part-003',
            'http://truyentranh.net/to-love-ru-darkness/To-Love-Ru-Darkness-Venus-part-004',
            'http://truyentranh.net/to-love-ru-darkness/To-Love-Ru-Darkness-Venus-part-005',
            'http://truyentranh.net/great-teacher-onizuka-paradise-lost/Great-Teacher-Onizuka--Paradise-Lost-Extra',
            'http://truyentranh.net/scandal-of-the-witch-vu-khi-khieu-goi/chap 007',
            'http://truyentranh.net/kazoku-gokko-gintama-dj/chap-oneshot',
            'http://truyentranh.net/hajimete-no-gal/chap-Bonus-01',
            'http://truyentranh.net/yeu-em-tu-cai-nhin-dau-tien/chap-Extra1',
            'http://truyentranh.net/yeu-em-tu-cai-nhin-dau-tien/chap-Extra2',
            'http://truyentranh.net/yeu-em-tu-cai-nhin-dau-tien/chap-Extra3',
            'http://truyentranh.net/getsuyobi-no-tawawa-sono/chap-extra'
        ];
    }

}
