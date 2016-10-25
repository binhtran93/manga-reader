<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;

use App\Http\Requests;

class TagController extends Controller {

    protected $tag;

    public function __construct(Tag $tag) {
        $this->tag = $tag;
    }

    /**
     * GET tag list
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTags(Request $request) {
        try {
            $tags = $this->tag->getTags();
            return response()->json(['status' => 1, 'tags' => $tags]);
        } catch(\Exception $e) {
            return response()->json(['status' => 0, 'message' => 'System error']);
        }
    }
}
