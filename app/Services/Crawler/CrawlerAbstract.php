<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Services\Crawler;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Description of CrawlerAbstract
 *
 * @author Tran
 */
abstract class CrawlerAbstract {

    protected $request;

    protected $domain;
    
    protected $pageListUrl;
    
    protected $pagePattern;
    
    protected $lastPageNumber;
    
    public function __construct(Client $request) {
        $this->request = $request;
    }
    
    protected function createCrawler($html) {
        return new Crawler($html);
    }

    protected function _resolvePageLink($index) {
        return preg_replace('/\<%.+?\%>/sm', $index, $this->pagePattern);
    }
}
