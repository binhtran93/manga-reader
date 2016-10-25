<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Author;

class AuthorControllerTest extends TestCase
{

    protected $authorCtrl;

    protected $author;

    public function setUp() {
        parent::setUp();

        // create mock
        $this->author = $this->getMockBuilder('App\Author')->getMock();
        $this->authorCtrl = $this->getMockBuilder('App\Http\Controllers\AuthorController')->setConstructorArgs([$this->author])->setMethods(null)->getMock();

    }

    public function test_get_author_return_success() {
        $array = [
            ['author_name' => 'binh'],
            ['author_name' => 'huong']
        ];

        $this->author->expects($this->once())->method('getAuthors')->will($this->returnValue($array));

        $response = $this->authorCtrl->getAuthors();
        $content = json_decode($response->getContent(), true);

        $this->assertTrue($response->getStatusCode() == 200);
        $this->assertTrue($content['status'] == 1);
    }

    public function test_get_authors_throwError() {
        $this->author->expects($this->once())->method('getAuthors')->will($this->throwException(new \Exception));

        $response = $this->authorCtrl->getAuthors();
        $content = json_decode($response->getContent(), true);

        $this->assertTrue($content['status'] == 0);
    }

}
