<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Services\Crawler;
/**
 * Description of CrawlerAbstract
 *
 * @author Tran
 */
abstract class CrawlerAbstract {
    
    protected $_mangaLinks = [];

    protected function _resolvePageLink($index, $pattern) {
        return preg_replace('/\<%.+?\%>/sm', $index, $pattern);
    }
    
    public function getMangaLinks() {
        return $this->_mangaLinks;
    }
}
