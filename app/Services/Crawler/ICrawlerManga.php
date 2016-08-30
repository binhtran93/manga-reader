<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Services\Crawler;
/**
 * Description of ICrawlerManga
 *
 * @author Tran
 */
interface ICrawlerManga {
    
    public function getMangaLinks();
    
    public function getChapterUrl();
    
    
}
