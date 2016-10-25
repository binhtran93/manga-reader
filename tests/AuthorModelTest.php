<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Author;

class AuthorModelTest extends TestCase
{

    protected $author;

    public function setUp() {
        parent::setUp();

        // create mock
        $this->author = $this->getMockBuilder('App\Author')->setMethods(null)->getMock();

    }

    public function test_store_empty_authors() {
        $authors = $this->author->storeAuthors([]);
        $this->assertEmpty($authors);
    }

    public function test_store_authors() {
        $authors = ['binh', 'ha', 'huong'];
        $authorsSaved = $this->author->storeAuthors($authors);
        $authorsNameSaved = $authorsSaved->map( function ($author) {
            return $author->author_name;
        });

        $this->assertEmpty(array_diff( $authors, $authorsNameSaved->toArray() ));

    }

    public function test_get_author_list() {
        factory(Author::class)->create(['author_name' => 'binh']);
        factory(Author::class)->create(['author_name' => 'huong']);
        factory(Author::class)->create(['author_name' => 'huong', 'is_deleted' => 1]);

        $author = $this->author->getAuthors();

        // expect $author instanceof Collection
        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Collection', $author);

        //expect return count array equals 2
        $this->assertTrue(count($author) == 2);
    }


}
