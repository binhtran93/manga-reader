<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Manga;

class MangaControllerTest extends TestCase
{
    protected $mangaCtrl;

    protected $manga;


    public function setUp() {
        parent::setUp();
        $this->manga = $this->createMock('App\Manga');
        $this->mangaCtrl = $this->getMockBuilder('App\Http\Controllers\MangaController')->setMethods(null)->setConstructorArgs([$this->manga])->getMock();
    }
        
    public function test_get_manga_list_return_json() {
        $request = $this->createMock('Illuminate\Http\Request');
        $this->manga->expects($this->once())->method('getMangaList');

        $response = $this->mangaCtrl->getMangaList($request);
        $content = json_decode($response->getContent(), true);

        $this->assertTrue( $response->getStatusCode() == 200 );
        $this->assertTrue( $content['status'] == 1 );
    }

    public function test_get_manga_by_id_not_provide_manga_id() {
        $request = $this->getMockBuilder('Illuminate\Http\Request')->getMock();
        $request->expects($this->once())->method('get')->with('mangaId')->will($this->returnValue(null));

        $response = $this->mangaCtrl->getMangaById($request);
        $content = json_decode($response->getContent(), true);

        $this->assertTrue($response->getStatusCode() == 200);
        $this->assertTrue($content['status'] == 0);
    }

    public function test_get_manga_by_id() {
        $request = $this->getMockBuilder('Illuminate\Http\Request')->getMock();
        $request->expects($this->once())->method('get')->with('mangaId')->will($this->returnValue(1));

        $response = $this->mangaCtrl->getMangaById($request);
        $content = json_decode($response->getContent(), true);

        $this->assertTrue($response->getStatusCode() == 200);
        $this->assertTrue($content['status'] == 1);
        $this->assertArrayHasKey('manga', $content);
    }
    
}
