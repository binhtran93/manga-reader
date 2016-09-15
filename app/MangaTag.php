<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MangaTag extends Model
{
    protected $table = 'manga_tag';
    
    protected $fillable = ['manga_id', 'tag_id'];
    
    /**
     * Store manga tags
     * @param type $tags
     * @return type
     */
    
    public function storeMangaTags($mangaTags) {
        $this->insert($mangaTags);
    }
    
}
