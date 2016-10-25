<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Manga;
use Illuminate\Support\Facades\DB;

class MangaController extends Controller
{
    
    protected $manga;

    public function __construct(Manga $manga) {
        $this->manga = $manga;
    }

    /**
     * GET manga list
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMangaList(Request $request) {
        $order = $request->get('order');
        $columnIndex = $request->get('column');
        $column = $this->manga->getColumn($columnIndex);
        $limit = $request->get('limit');
        $offset = $request->get('offset');
        $search = $request->get('search');
        $draw = $request->get('draw');
        
        
        try {
            $list = $this->manga->getMangaList($limit, $offset, $column, $order, $search);
            $count = $this->manga->getMangaTotal($search);
            
            return response()->json(['status' => 1, 'data' => $list, 'recordsTotal' => $count, 'recordsFiltered' => $count, 'draw' => $draw]);
            
        } catch (\Exception $ex) {
            return response()->json(['status' => 0, 'message' => 'Internal error']);
        }
    }

    /**
     * GET manga by ID
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMangaById(Request $request) {
        $mangaId = $request->get('mangaId');

        if ( !$mangaId ) {
            return response()->json(['status' => 0, 'message' => 'Parameters required']);
        }

        try {
            $manga = $this->manga->getMangaById($mangaId);
            return response()->json(['status' => 1, 'manga' => $manga]);
        } catch (\Exception $e) {
            return response()->json(['staus' => 0, 'message' => 'System error', 'reason' => $e->getMessage()]);
        }
    }



}
