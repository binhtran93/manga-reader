<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Manga extends Model
{
    
    protected $table = 'manga';
    
    protected $fillable = ['manga_name', 'translator', 'status', 'slug', 'description', 'thumbnail_uri', 'view_count', 'like_count'];
    
    /**
     * Store manga and return record
     * @param array $mangaData ['title' => 'onepiece', 'status' => 'full', 'description' => 'bbla']
     * @return Collection
     */
    public function storeManga($mangaData) {
        $slug = implode( '-', explode(' ', trim($mangaData["manga_name"]) ) );
        $mangaData['slug'] = $slug;

        $manga = $this->create($mangaData);
        
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

        return $query->get();
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


    /**
     * @param $mangaId
     * @return Collection
     */
    public function getMangaById($mangaId) {
        return $this->where(['id' => $mangaId, 'is_deleted' => 0])->with('tags')->with('authors')->first();
    }

    /**
     * Many to many relationship with Tag model
     */
    public function tags() {
        return $this->belongsToMany('App\Tag', 'manga_tag', 'manga_id', 'tag_id');
    }

    /**
     * Many to many relationship with Author model
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function authors() {
        return $this->belongsToMany('App\Author', 'manga_author', 'manga_id', 'author_id');
    }

    /**
     * @param $mangaId
     * @param $newManga Array
     * @return mixed
     * @throws \Exception
     */
    public function updateManga($mangaId, $newManga) {
        if ( !$mangaId ) {
            throw new \Exception('Manga Id not provided');
        }

        $manga = $this->getMangaById($mangaId);
        if ( !$manga ) {
            throw new \Exception("Manga record not exist with id: {$mangaId}");
        }

        $newManga['slug'] = implode( '-', explode(' ', $newManga['manga_name']) );
        $updated = $this->where(['id' => $mangaId, 'is_deleted' => 0])->update($newManga);

        return $updated;
    }
}
