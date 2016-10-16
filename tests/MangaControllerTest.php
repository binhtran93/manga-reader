<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Manga;

class MangaControllerTest extends TestCase
{
    protected $mangaCtrl;


    public function setUp() {
        parent::setUp();
        $this->manga = $this->createMock('App\Manga');
        $this->mangaCtrl = app()->make('App\Http\Controllers\MangaController', [$this->manga]);
    }
        
    public function test_get_manga_list_return_json() {
//        $fakeArray = [
//            ['manga_name' => 'manga_1', 'status' => 'full'],
//            ['manga_name' => 'manga_2', 'status' => 'full'],
//        ];
//        $request = $this->createMock('Illuminate\Http\Request');
//        $this->manga->expects($this->any())->method('getMangaList')->will($this->returnValue($fakeArray));
//        
//        $response = $this->mangaCtrl->getMangaList($request);
//
//        $this->assertTrue( $response->getStatusCode() == 200 );
//        $this->assertTrue( json_encode($fakeArray) == $response->getContent() );
    }
    
}
