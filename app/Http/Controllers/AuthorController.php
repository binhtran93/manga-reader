<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Author;

class AuthorController extends Controller
{
    protected $author;

    public function __construct(Author $author) {
        $this->author = $author;
    }

    public function getAuthors() {
        try {
            $authors = $this->author->getAuthors();
            return response()->json(['status' => 1, 'authors' => $authors]);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => 'System error']);
        }
    }
}
