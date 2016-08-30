<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class MangaLink extends Model {
    
    protected $table = 'manga_link';
    
    public function insertOnNotExits($mangaLinks) {
        $chunks = array_chunk($mangaLinks, 100);
        
        foreach ( $chunks as $chunk ) {
            $values = $this->_buildSqlValue($chunk);
            $params = $this->_flattenArray($chunk);

            $query = "INSERT INTO $this->table (`link`, `manga_name`, `domain`, `created_at`, `updated_at`) VALUES $values ON DUPLICATE KEY UPDATE `updated_at` = values(updated_at)";

            DB::statement($query, $params);
        }
    }
    
    protected function _buildSqlValue($mangaList) {
        $values = '';
        foreach ( $mangaList as $manga ) {
            $str = '('.  rtrim( str_repeat("?,", count($manga) + 2), ',' ) . ')';
            $values .= ',' . $str;
        }
        
        return trim($values, ',');
    }
    
    protected function _flattenArray($mangaList) {
        
        $result = [];
        foreach ( $mangaList as $manga ) {
            foreach ( $manga as $col ) {
                $result[] = $col;
            }
            $result[] = $this->_getCurrentDateTime();
            $result[] = $this->_getCurrentDateTime();
        }
        
        return $result;
    }
    
    protected function _getCurrentDateTime() {
        return Carbon::now()->toDateTimeString();
    }
    
    public function getAllMangaLink() {
        return $this->where('is_deleted', 0);
    }
}
