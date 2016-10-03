<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChapterMedia extends Model
{
    protected $table = 'chapter_media';
    
    protected $fillable = ['chapter_id', 'uri'];
    
    public function storeChapterMedias($chapterMedias) {
        $chapterStorage = [];
        
        foreach ( $chapterMedias as $key => $chapterMedia ) {
            foreach ( $chapterMedia as $chapter ) {
                $chapterStorage[] = [
                    'chapter_id' => $key,
                    'uri' => $chapter,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }
        }
        
        foreach ( array_chunk($chapterStorage, 200) as $storage ) {
            $this->insert($storage);
        }
    }
    
    public function findByChapterMediaAndChapterNumber($chapterMedias) {
        if ( is_array($chapters) ) {
            return $this->whereIn('chapter_number', $chapters)->where('manga_id', $mangaId);
        }
        
        return $this->where('chapter_number', $chapters)->where('manga_id', $mangaId);
    }
}
