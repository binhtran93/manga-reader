<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manga extends Model
{
    
    protected $table = 'manga';
    
    protected $fillable = ['manga_name', 'translator', 'staus', 'slug', 'description', 'thumbnail_uri', 'view_count', 'like_count'];
    
    public function storeManga($title, $status, $description, $thumbnail) {
        $manga = $this->findByTitle($title)->first();
        if ( !empty($manga) ) {
            return $manga;
        }
        
        $slug = implode( '-', explode(' ', trim($title) ) );
        $manga = $this->create([
            'manga_name' => $title,
            'status' => $status,
            'description' => $description,
            'thumbnail_uri' => $thumbnail,
            'slug' => $slug
        ]);
        
        return $manga;
    }
    
    public function findByTitle($title) {
        return $this->where('manga_name', $title);
    }
}
