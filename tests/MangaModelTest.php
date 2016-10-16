<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Manga;

class MangaModelTest extends TestCase
{
    
    protected $manga;

    public function setUp() {
        parent::setUp();
        $this->manga = app()->make('App\Manga');
    }
    
    public function test_find_manga_by_title() {
        factory(Manga::class)->create(['manga_name' => 'manga']);
        $mangaFounded = $this->manga->findByTitle('manga');
        
        $this->assertTrue($mangaFounded->first()->manga_name == 'manga');
    }
    
    
    public function test_get_manga_list_with_offset_limit() {
        $offset = 0;
        $limit = 10;
        factory(Manga::class, 50)->create();
        $mangaMock = $this->getMockBuilder('App\Manga')->setMethods(null)->getMock();
        $mangaList = $mangaMock->getMangaList($limit, $offset, null, null, null)->get();
        
        $this->assertTrue( count($mangaList) == 10 );
        
        $offset = 48;
        $limit = 10;
        
        $mangaList = $mangaMock->getMangaList($limit, $offset, null, null, null)->get();
        $this->assertTrue( count($mangaList) == 2 );
    }
    
    public function test_get_manga_list_with_manga_name_order() {
        $column = 'manga_name';
        $order = 'ASC';
        factory(Manga::class)->create(['manga_name' => 'a']);
        factory(Manga::class)->create(['manga_name' => 'b']);
        factory(Manga::class)->create(['manga_name' => 'c']);
        
        $mangaList = $this->manga->getMangaList(null, null, $column, $order, null)->get();
        
        $this->assertTrue($mangaList->get(0)->manga_name == 'a');
        $this->assertTrue($mangaList->get(2)->manga_name == 'c');
        
        $column = 'manga_name';
        $order = 'DESC';
        $mangaList = $this->manga->getMangaList(null, null, $column, $order, null)->get();
        
        $this->assertTrue($mangaList->get(0)->manga_name == 'c');
        $this->assertTrue($mangaList->get(2)->manga_name == 'a');
    }
    
    public function test_get_manga_list_with_search_field() {
        $search = 'bi';
        
        factory(Manga::class)->create(['manga_name' => 'a']);
        factory(Manga::class)->create(['manga_name' => 'binh']);
        factory(Manga::class)->create(['manga_name' => 'bi']);
        
        $mangaList = $this->manga->getMangaList(null, null, null, null, $search)->get();
        
        $this->assertEquals( count($mangaList), 2 );
        
        foreach ( $mangaList as $manga ) {
            $this->assertRegExp('/bi/i', $manga->manga_name);
        }
    }
    
    public function test_get_total_manga_list() {
        $search = 'bi';
        
        factory(Manga::class)->create(['manga_name' => 'a']);
        factory(Manga::class)->create(['manga_name' => 'binh']);
        factory(Manga::class)->create(['manga_name' => 'bi']);
        
        $total = $this->manga->getMangaTotal($search);
        
        $this->assertEquals( $total, 2 );
    }
}
