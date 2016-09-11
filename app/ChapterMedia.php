<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChapterMedia extends Model
{
    protected $table = 'chapter_media';
    
    protected $fillable = ['chapter_id', 'uri'];
    
    public function storeChapterMedias($chapterMedias) {
        dd($chapterNumber);
        $chapterStorage = [];
        
        $chapersExist = $this->findByChapterMediaAndChapterNumber($chapterMedias)->get();
        $chapersExistNumber = [];
        
        foreach ( $chapersExist as $chapter ) {
            $chapersExistNumber[] = $chapter;
        }
        
        $newChapters = array_filter($chapters, function($chapter) use ($chapersExistNumber) {
            return ( !in_array($chapter, $chapersExistNumber) ); 
        });
        
        foreach ( $newChapters as $chapter ) {
            $chapterStorage[] = [
                'manga_id' => $mangaId,
                'chapter_number' => $chapter,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        
        $this->insert($chapterStorage);
        return $this->whereIn('chapter_number', $chapters)->where('manga_id', $mangaId)->get();
    }
    
    public function findByChapterMediaAndChapterNumber($chapterMedias) {
        if ( is_array($chapters) ) {
            return $this->whereIn('chapter_number', $chapters)->where('manga_id', $mangaId);
        }
        
        return $this->where('chapter_number', $chapters)->where('manga_id', $mangaId);
    }
}
