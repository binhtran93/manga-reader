<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Manga;
use App\Tag;
use App\MangaTag;
use App\Author;
use App\MangaAuthor;

class MangaModelTest extends TestCase
{
    
    protected $manga;

    public function setUp() {
        parent::setUp();
        $this->manga = app()->make('App\Manga');
    }

    public function test_store_manga() {
        $mangaData = [
            'manga_name' => 'one piece',
            'status' => 'full',
            'slug' => 'one-piece',
            'description' => 'bla bla bla'
        ];

        $mangaMock = $this->getMockBuilder('App\Manga')->setMethods(null)->getMock();
        $mangaSaved = $mangaMock->storeManga($mangaData);

        $this->assertEquals( $mangaSaved->manga_name, $mangaData["manga_name"] );
        $this->assertEquals( $mangaSaved->status, $mangaData["status"] );
        $this->assertEquals( $mangaSaved->slug, $mangaData["slug"] );
        $this->assertEquals( $mangaSaved->description, $mangaData["description"] );
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
        $mangaList = $mangaMock->getMangaList($limit, $offset, null, null, null);
        
        $this->assertTrue( count($mangaList) == 10 );
        
        $offset = 48;
        $limit = 10;
        
        $mangaList = $mangaMock->getMangaList($limit, $offset, null, null, null);
        $this->assertTrue( count($mangaList) == 2 );
    }
    
    public function test_get_manga_list_with_manga_name_order() {
        $column = 'manga_name';
        $order = 'ASC';
        factory(Manga::class)->create(['manga_name' => 'a']);
        factory(Manga::class)->create(['manga_name' => 'b']);
        factory(Manga::class)->create(['manga_name' => 'c']);
        
        $mangaList = $this->manga->getMangaList(null, null, $column, $order, null);
        
        $this->assertTrue($mangaList->get(0)->manga_name == 'a');
        $this->assertTrue($mangaList->get(2)->manga_name == 'c');
        
        $column = 'manga_name';
        $order = 'DESC';
        $mangaList = $this->manga->getMangaList(null, null, $column, $order, null);
        
        $this->assertTrue($mangaList->get(0)->manga_name == 'c');
        $this->assertTrue($mangaList->get(2)->manga_name == 'a');
    }
    
    public function test_get_manga_list_with_search_field() {
        $search = 'bi';
        
        factory(Manga::class)->create(['manga_name' => 'a']);
        factory(Manga::class)->create(['manga_name' => 'binh']);
        factory(Manga::class)->create(['manga_name' => 'bi']);
        
        $mangaList = $this->manga->getMangaList(null, null, null, null, $search);
        
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

    public function test_get_manga_by_id() {
        factory(Manga::class)->create(['id' => 1, 'manga_name' => 'binh']);
        factory(Tag::class)->create(['id' => '1', 'tag_name' => 'ecchi']);
        factory(Tag::class)->create(['id' => '2', 'tag_name' => '16+']);
        factory(MangaTag::class)->create(['manga_id' => '1', 'tag_id' => '1']);
        factory(MangaTag::class)->create(['manga_id' => '1', 'tag_id' => '2']);

        factory(Author::class)->create(['id' => '1', 'author_name' => 'ecchi']);
        factory(MangaAuthor::class)->create(['manga_id' => '1', 'author_id' => '1']);

        $mangaMock = $this->getMockBuilder('App\Manga')->setMethods(null)->getMock();

        $manga = $mangaMock->getMangaById(1)->toArray();

        $this->assertArrayHasKey('tags', $manga);
        $this->assertTrue(count($manga['tags']) == 2);

        $this->assertArrayHasKey('authors', $manga);
        $this->assertTrue(count($manga['authors']) == 1);
    }

    public function test_update_manga_without_manga_id_will_throw_exception() {
        $mangaId = null;
        $mangaMock = $this->getMockBuilder('App\Manga')->setMethods(null)->getMock();

        $this->expectException(\Exception::class);

        $mangaMock->updateManga($mangaId, []);

    }

    public function test_update_manga_with_manga_id_not_exist_in_db() {
        $mangaId = 1;
        $mangaMock = $this->getMockBuilder('App\Manga')->setMethods(['getMangaById'])->getMock();

        $mangaMock->expects(($this->once()))->method('getMangaById')->with($this->anything())->will($this->returnValue(null));
        $this->expectException(\Exception::class);

        $mangaMock->updateManga($mangaId, []);
    }

    public function test_update_manga_process() {
        $value = ['id' => 1, 'manga_name' => 'binh', 'status' => 'full', 'description' => 'bla bla', 'is_deleted' => 0];
        $newValue = ['manga_name' => 'binh new', 'status' => 'continue', 'description' => 'new'];
        factory(Manga::class)->create($value);
        $mangaId = 1;

        $mangaMock = $this->getMockBuilder('App\Manga')->setMethods(['getMangaById'])->getMock();
        $mangaMock->expects(($this->once()))->method('getMangaById')->with($mangaId)->will($this->returnValue($value));

        $mangaMock->updateManga($mangaId, $newValue);

        $mangaUpdated = $mangaMock->where(['id' => 1])->first();

        $this->assertEquals($mangaUpdated->manga_name, 'binh new');
        $this->assertEquals($mangaUpdated->status, 'continue');
        $this->assertEquals($mangaUpdated->description, 'new');
        $this->assertEquals($mangaUpdated->slug, 'binh-new');
    }
}
