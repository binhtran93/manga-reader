<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table = 'author';
    
    protected $fillable = ['author_name'];

    /**
     * Save author to DB
     * @param $authors
     * @return array
     */
    public function storeAuthors($authors) {
        if ( empty($authors) ) {
            return [];
        }
        
        $authorsExist = $this->findByName($authors)->get();
        $authorExistsName = [];
        
        foreach ( $authorsExist as $author ) {
            $authorExistsName[] = $author->author_name;
        }
        
        $newAuthors = array_filter($authors, function($author) use (&$authorExistsName) {
            return ( !in_array($author, $authorExistsName) );
        });
        
        $authorsStorage = [];
        foreach ( $newAuthors as $author ) {
            $authorsStorage[] = [
                'author_name' => $author, 
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        
        $this->insert($authorsStorage);
        return $this->whereIn('author_name', $authors)->get();
    }

    /**
     * Find author by name
     * @param $authors
     * @return mixed
     */
    public function findByName($authors) {
        if (is_array($authors) ) {
            return $this->whereIn('author_name', $authors);
        }
        
        return $this->where('author_name', $authors);
    }

    /**
     * Get authors
     */
    public function getAuthors() {
        return $this->where(['is_deleted' => 0])->get();
    }
}
