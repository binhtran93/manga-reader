<?php

/**
 * Created by PhpStorm.
 * User: Tran
 * Date: 22/10/2016
 * Time: 23:56 PM
 */
class ChapterModelTest extends TestCase
{

    protected $chapter;

    public function setUp() {
        parent::setUp();

        // create mock
        $this->chapter = $this->getMockBuilder('App\Chapter')->setMethods(null)->getMock();

    }

    public function test_store_chapters() {
        $chapters = ['001', '002', '003'];
        $mangaId = 1;

        $chaptersSaved = $this->chapter->storeChapters( $chapters, $mangaId );
        $chaptersSavedNumber = $chaptersSaved->map(function ($chapter) {
            return $chapter->chapter_number;
        });

        $this->assertEmpty( array_diff($chapters, $chaptersSavedNumber->toArray()) );

    }

}