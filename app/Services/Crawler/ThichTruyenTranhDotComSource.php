<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Services\Crawler;

use App\Services\Crawler\ICrawlerManga;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Description of ThichTruyenTranhDotComSource
 *
 * @author Tran
 */
class ThichTruyenTranhDotComSource extends CrawlerAbstract implements ICrawlerManga {
    
    CONST DOMAIN = 'http://thichtruyentranh.com/';
    CONST MAIN_PAGE_CRAWL = 'truyen-moi-nhat/';
    CONST PAGE_PATTERN = 'trang.<%number%>.html';
    CONST MAX_PAGE = 221;
    
    
    public function getMangaLinks() {
        for ( $index = 0; $index < self::MAX_PAGE; $index++ ) {
            $pageNum = $index + 1;
            $pageString = $this->_resolvePageLink($pageNum, self::PAGE_PATTERN);
            $pageUrl = self::DOMAIN . self::MAIN_PAGE_CRAWL . $pageString;
            
            $mangaList = $this->_getMangaContainer($pageUrl);
            
            foreach( $mangaList as $manga ) {
                $mangaEle = new Crawler($manga);
                $mangaUrl = $mangaEle->filter('.tile')->attr('href');
                $mangaTitle = $mangaEle->filter('.tile')->attr('title');
                
                $this->_mangaLinks[] = ['link' => $mangaUrl, 'manga_name' => $mangaTitle, 'domain' => self::DOMAIN];
            }
        }
        
        return $this->_mangaLinks;
    }
    
    protected function _getMangaContainer($pageUrl) {
        $request = new Client();
        $response = $request->request('GET', $pageUrl);

        $crawler = new Crawler( $response->getBody()->getContents() );
        $mangaList = $crawler->filter('.newsContent');
        
        return $mangaList;
    }

    public function getChapterUrl() {
        
    }
    
    public function getMangaInfo($domain, $uri) {
        $url = $domain . $uri;
        $request = new Client();
        
        $response = $request->request('GET', $url);
        $body = $response->getBody()->getContents();
        
        $crawler = new Crawler( $body );
        $ul = $crawler->filter('.ullist_item')->getNode(0);
        
        $infoEle = new Crawler( $ul );
        $elements = $infoEle->filter('li');

        $authors = [];
        $status = 'continue';
        $translator = '';
        $tags = [];
        $chapter = [];
        
        foreach( $elements as $index => $element ) {
            if ( $index == count($elements) - 1 ) continue;
            
            $li = new Crawler($element);      
            $item1 = $li->filter('.item1');
            $label = $item1->text();
            $item2 = $li->filter('.item2');
            
            if ( strcasecmp($label, 'Tác giả') == 0 ) {
                $authors = $item2->filter('a')->each(function(Crawler $node, $index) {
                    return $node->attr('title');
                });
            }
            
            if ( strcasecmp($label, 'Tình trạng') == 0 ) {
                $statusText = $item2->filter('span')->text();
                $status = ( strcasecmp($statusText, 'Còn tiếp') == 0 ) ? $status = 'continue' : $status = 'full';
            }
            
            if ( strcasecmp($label, 'Thể loại') == 0 ) {
                $tags = $item2->filter('a')->each(function(Crawler $node, $index) {
                    return $node->attr('title');
                });
            }
            
            if ( strcasecmp($label, 'Nhóm dịch') == 0 ) {
                $translator = trim( trim($item2->text(), ':'), ' ' );
            }
        }
        
        $listChap = $crawler->filter('.ul_listchap li');
        foreach( $listChap as $chap ) {
            $chapEle = new Crawler($chap);
            
        }
//        return [
//            
//        ]
    }

}
