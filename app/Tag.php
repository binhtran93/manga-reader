<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tag';
    
    protected $fillable = ['tag_name'];

    public function mangas() {
        return $this->belongsToMany('App\Manga', 'manga_tag', 'tag_id', 'manga_id');
    }
    
    /**
     * Store tag and return tags
     * @param array $tags ['ecchi', '16+']
     * @return Collection
     */
    public function storeTags($tags) {
        $tagsExists = $this->getTagByName($tags);
        $tagsExistsName = [];
        
        foreach( $tagsExists as $tag ) {
            $tagsExistsName[] = $tag->tag_name;
        }
        
        $newTags = array_filter($tags, function($tag) use ($tagsExistsName) {
            return ( !in_array($tag, $tagsExistsName) );
        });
        
        $tagsStorage = [];
        foreach ( $newTags as $tag ) {
            $tagsStorage[] = [
                'tag_name' => $tag, 
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        
        $this->insert($tagsStorage);
        return $this->whereIn('tag_name', $tags)->get();
    }

    /**
     * Get tag by name
     * @param $name
     * @return Collection
     */
    public function getTagByName($name) {
        if ( is_array($name) ) {
            return $this->whereIn('tag_name', $name)->get();
        }
        
        return $this->where('tag_name', $name)->get();
    }

    public function getTags() {
        return $this->get();
    }
 }
