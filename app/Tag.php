<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tag';
    
    protected $fillable = ['tag_name'];
    
    /**
     * Store tag and return tags
     * @param type $tags
     * @return type
     */
    public function storeTags($tags) {
        $tagsExists = $this->getTagByName($tags)->get();
        $tagsExistsName = [];
        
        foreach( $tagsExists as $tag ) {
            $tagsExistsName[] = $tag->tag_name;
        }
        
        $newTags = array_filter($tags, function($tag) use ($tagsExistsName) {
            return ( !in_array($tag, $tagsExistsName) );
        });
        
        dd($newTags);
//        $tagsStorage = [];
//        foreach ( $tags as $tag ) {
//            $tagsStorage[]['tag_name'] = $tag;
//        }
//        
//        $this->insert($tagsStorage);
//        return $this->whereIn('tag_name', $tags)->get();
    }
    
    public function getTagByName($name) {
        if ( is_array($name) ) {
            return $this->whereIn('tag_name', $name);
        }
        
        return $this->where('tag_name', $name);
    }
 }
