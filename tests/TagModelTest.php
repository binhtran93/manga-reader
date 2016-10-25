<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Tag;

class TagModelTest extends TestCase
{

    protected $tag;

    public function setUp() {
        parent::setUp();
    }

    function test_get_tags_list() {
        factory(Tag::class)->create(['tag_name'=> '16+']);
        factory(Tag::class)->create(['tag_name'=> 'ecchi']);

        $tagMock = $this->getMockBuilder('App\Tag')->setMethods(null)->getMock();
        $tags = $tagMock->getTags();
        $this->assertTrue(count($tags) == 2);
    }


    function test_store_tag_to_db_with_no_tag_exit() {
        $tags = ['ecchi', 'action', 'hentai'];

        $tagMock = $this->getMockBuilder('App\Tag')->setMethods(null)->getMock();

        $tagsSaved = $tagMock->storeTags($tags);
        $tagsNameSaved = [];
        foreach ( $tagsSaved as $tag ) {
            $tagsNameSaved[] = $tag->tag_name;
        }

        $this->assertEmpty( array_diff($tags, $tagsNameSaved) );
        $this->assertTrue( count($tagsSaved) == 3 );
    }

    function test_store_tag_to_db_with_tag_exits() {
        // create tags exists in DB
        factory(Tag::class)->create(['tag_name'=> '16+']);
        factory(Tag::class)->create(['tag_name'=> 'ecchi']);

        $tags = ['ecchi', 'action', 'hentai'];

        $tagMock = $this->getMockBuilder('App\Tag')->setMethods(null)->getMock();

        $tagsSaved = $tagMock->storeTags($tags);

        $tagsNameSaved = [];
        foreach ( $tagsSaved as $tag ) {
            $tagsNameSaved[] = $tag->tag_name;
        }

        $this->assertEmpty( array_diff($tags, $tagsNameSaved) );
        $this->assertTrue( count($tagsSaved) == 3 );
    }

}
