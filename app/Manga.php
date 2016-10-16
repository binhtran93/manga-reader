<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manga extends Model
{
    
    protected $table = 'manga';
    
    protected $fillable = ['manga_name', 'translator', 'status', 'slug', 'description', 'thumbnail_uri', 'view_count', 'like_count'];
    
    /**
     * Store manga
     * @param type $title
     * @param type $status
     * @param type $description
     * @param type $thumbnail
     * @return type
     */
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
    
    /**
     * Get list manga
     * @param type $limit
     * @param type $offset
     * @param type $column
     * @param type $order
     * @param type $search
     * @return type
     */
    public function getMangaList($limit, $offset, $column, $order, $search) {
        $query = $this;
        
        if ( $limit !== null && $offset !== null ) {
            $query = $query->skip($offset)->take($limit);
        }
        
        if ( $order !== null && $column !== null ) {
            $query = $query->orderBy($column, $order);
        }
        
        if ( $search !== null && $search != '' ) {
            $query = $query->where('manga_name', 'like', "%{$search}%");
        }

        return $query;
    }
    
    /**
     * Get total record manga
     * @param type $search
     * @return type
     */
    public function getMangaTotal($search) {
        $query = $this;
        
        if ( $search !== null && $search != '' ) {
            $query = $query->where('manga_name', 'like', "%{$search}%");
        }
        
        return count( $query->get() );
    }
    
    /**
     * get column return to client
     * @return type
     */
    public function getColumn($index) {
        $columns = ['manga_name', 'slug', 'view', 'like'];
        return $columns[$index];
    }
}
