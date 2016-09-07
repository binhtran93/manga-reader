<?php

namespace App\Services;

use App\Chapter;
use App\ChapterMedia;
use App\Manga;
use App\Tag;
use App\Author;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of createMangaService
 *
 * @author Binh.Tran
 */
class StoreMangaService {
    
    protected $manga;
    
    protected $tag;
    
    protected $author;
    
    protected $chapter;
    
    protected $chapterMedia;
    
    public function __construct(
        Manga $manga, 
        Tag $tag, 
        Author $author, 
        Chapter $chapter, 
        ChapterMedia $chapterMedia
    ) {
        $this->manga = $manga;
        $this->tag = $tag;
        $this->author = $author;
        $this->chapter = $chapter;
        $this->chapterMedia = $chapterMedia;
    }
    
    public function storeMangaInformaiton($mangaInfo) {
        $title = $mangaInfo['title'];
        $tags = $mangaInfo['tags'];
        $status = $mangaInfo['status'];
        $author = $mangaInfo['author'];
        $chapters = $mangaInfo['chapters'];
        
        $tagsRecords = $this->_storeTags($tags);
    }
    
    protected function _storeTags($tags) {
        return $this->tag->storeTags($tags);
    }
    
    protected function _storeAuthor() {
        
    }
    
    protected function _storeChapter() {
        
    }
    
    protected function _storeChapterMedia() {
        
    }
    
    protected function _storeManga() {
        
    }
}
