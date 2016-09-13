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
        $authors = $mangaInfo['authors'];
        $chapters = $mangaInfo['chapters'];
        $description = $mangaInfo['description'];
        $thumbnail = $mangaInfo['thumbnail'];
        
        $tagsRecords = $this->_storeTags($tags);
        $mangaRecord = $this->_storeManga($title, $status, $description, $thumbnail);
        $authorRecords = $this->_storeAuthors($authors);
        $chapters = $this->_storeChapters($chapters, $mangaRecord->id);
    }
    
    protected function _storeTags($tags) {
        return $this->tag->storeTags($tags);
    }
    
    protected function _storeAuthors($authors) {
        return $this->author->storeAuthors($authors);
    }
    
    protected function _storeChapters($chapters, $mangaId) {
        $chaptersStorage = [];
        $chapterMediaStorage = [];
        $storage = [];
        
        if ( empty($chapters) ) {
            return;
        }
        
        foreach ( $chapters as $chapNumber => $chapterMedia ) {
            $chaptersStorage[] = $chapNumber;
            $chapterMediaStorage[$chapNumber] = $chapterMedia;
        }
        
        // store chapter
        $chaptersStored = $this->chapter->storeChapters($chaptersStorage, $mangaId);
        
        foreach ( $chaptersStored as $chapter ) {
            $storage[$chapter->id] = $chapterMediaStorage[$chapter->chapter_number];
        }
        
        // store chapter media
        $chapterMediasStored = $this->chapterMedia->storeChapterMedias($storage);
    }
    
    protected function _storeManga($title, $status, $description, $thumbnail) {
        return $this->manga->storeManga($title, $status, $description, $thumbnail);
    }
}
