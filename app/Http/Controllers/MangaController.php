<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Manga;

class MangaController extends Controller
{
    
    protected $manga;

    public function __construct(Manga $manga) {
        $this->manga = $manga;
    }
    
    public function getMangaList(Request $request) {
        $order = $request->get('order');
        $columnIndex = $request->get('column');
        $column = $this->manga->getColumn($columnIndex);
        $limit = $request->get('limit');
        $offset = $request->get('offset');
        $search = $request->get('search');
        $draw = $request->get('draw');
        
        
        try {
            $list = $this->manga->getMangaList($limit, $offset, $column, $order, $search)->get();
            $count = $this->manga->getMangaTotal($search);
            
            return response()->json(['status' => 1, 'data' => $list, 'recordsTotal' => $count, 'recordsFiltered' => $count, 'draw' => $draw]);
            
        } catch (\Exception $ex) {
//            echo $ex;
            return response()->json(['status' => 0, 'message' => 'Internal error']);
        }
    }
}
