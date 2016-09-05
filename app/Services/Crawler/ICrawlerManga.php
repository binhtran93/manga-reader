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
    
    /**
     * Get manga link profile
     */
    public function getAllMangaUrl();
    
    public function getManga($url);

    public function getMangaListContainer($pageUrl);
    
    public function getMangaContainer($html);
    
    public function getAuthor($mangaContainer);
    
    public function getTitle($mangaContainer);
    
    public function getTranslator($mangaContainer);
    
    public function getMangaStatus($mangaContainer);
    
    public function getTags($mangaContainer);
    
    public function getChapterContainer();
    
    public function getChapterUrl();
    
    
}
