<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $table = 'chapter';
    
    protected $fillable = ['manga_id', 'chapter_number'];
    
    public function storeChapters($chapters, $mangaId) {
        $chapterStorage = [];
        
        $chapersExist = $this->findByChapNumberAndManga($chapters, $mangaId);
        $chapersExistNumber = [];
        
        foreach ( $chapersExist as $chapter ) {
            $chapersExistNumber[] = $chapter->chapter_number;
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
    
    public function findByChapNumberAndManga($chapters, $mangaId) {
        if ( is_array($chapters) ) {
            return $this->whereIn('chapter_number', $chapters)->where('manga_id', $mangaId)->get();
        }
        
        return $this->where('chapter_number', $chapters)->where('manga_id', $mangaId)->get();
    }
}
