<?php

namespace App\Services;

use App\Chapter;
use App\ChapterMedia;
use App\Manga;
use App\Tag;
use App\Author;
use App\MangaLink;
use App\MangaTag;
use App\MangaAuthor;

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
    
    protected $mangaLink;
    
    protected $mangaTag;
    
    protected $mangaAuthor;

    public function __construct(
        Manga $manga, 
        Tag $tag, 
        Author $author, 
        Chapter $chapter, 
        ChapterMedia $chapterMedia,
        MangaLink $mangaLink,
        MangaTag $mangaTag,
        MangaAuthor $mangaAuthor
    ) {
        $this->manga = $manga;
        $this->tag = $tag;
        $this->author = $author;
        $this->chapter = $chapter;
        $this->chapterMedia = $chapterMedia;
        $this->mangaLink = $mangaLink;
        $this->mangaTag = $mangaTag;
        $this->mangaAuthor = $mangaAuthor;
    }
    
    public function storeMangaInformaiton($mangaInfo) {
        $title =        ( isset($mangaInfo['title']) )      ? ($mangaInfo['title'])     : '';
        $tags =         ( isset($mangaInfo['tags']) )       ? $mangaInfo['tags']        : [];
        $status =       ( isset($mangaInfo['status']) )     ? $mangaInfo['status']      : 'continue';
        $authors =      ( isset($mangaInfo['authors']) )    ? $mangaInfo['authors']     : [];
        $chapters =     ( isset($mangaInfo['tags']) )       ? $mangaInfo['tags']        : [];
        $description =  ( isset($mangaInfo['description'])) ? $mangaInfo['description'] : '';
        $thumbnail =    ( isset($mangaInfo['thumbnail']) )  ? $mangaInfo['thumbnail']   : '';
        $mangaTags =    [];
        $mangaAuthors = [];
        
        $tagsRecords = $this->_storeTags($tags);
        $mangaRecord = $this->_storeManga(['manga_name' => $title, 'status' => $status, 'description' => $description, 'thumbnail' => $thumbnail]);
        $authorRecords = $this->_storeAuthors($authors);
        $this->_storeChapters($chapters, $mangaRecord->id);
        
        foreach ( $tagsRecords as $tag ) {
            $mangaTags[] = [
                'manga_id' => $mangaRecord->id,
                'tag_id' => $tag->id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        
        foreach ( $authorRecords as $author ) {
            $mangaAuthors[] = [
                'manga_id' => $mangaRecord->id,
                'author_id' => $author->id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        
        $this->_storeMangaTags($mangaTags);
        $this->_storeMangaAuthors($mangaAuthors);
        
        // mark crawled
        $this->mangaLink->markCrawled($mangaRecord->id);
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
        $this->chapterMedia->storeChapterMedias($storage);
    }
    
    protected function _storeManga($title, $status, $description, $thumbnail) {
        return $this->manga->storeManga($title, $status, $description, $thumbnail);
    }
    
    protected function _storeMangaTags($mangaTags) {
        return $this->mangaTag->storeMangaTags($mangaTags);
    }
    
    protected function _storeMangaAuthors($mangaAuthors) {
        return $this->mangaAuthor->storeMangaAuthors($mangaAuthors);
    }
}
