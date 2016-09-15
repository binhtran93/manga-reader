<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MangaAuthor extends Model
{
    protected $table = 'manga_author';
    
    protected $fillable = ['manga_id', 'author_id'];
    
    /**
     * Store manga tags
     * @param type $tags
     * @return type
     */
    
    public function storeMangaAuthors($mangaAuthors) {
        $this->insert($mangaAuthors);
    }
}
