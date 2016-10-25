<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Tag;

class TagControllerTest extends TestCase
{

    protected $tagCtrl;

    protected $tag;

    public function setUp() {
        parent::setUp();

        // create mock
        $this->tag = $this->getMockBuilder('App\Tag')->getMock();
        $this->tagCtrl = $this->getMockBuilder('App\Http\Controllers\TagController')->setConstructorArgs([$this->tag])->setMethods(null)->getMock();

    }

    function test_get_tags_list_return_json() {
        $array = [
            ['tag_name' => '16+'],
            ['tag_name' => 'ecchi']
        ];

        $request = $this->createMock('\Illuminate\Http\Request');

        // check Tag->getTags hit once;
        $this->tag->expects($this->once())->method('getTags')->will($this->returnValue($array));

        $response = $this->tagCtrl->getTags($request);

        // check return count
        $this->assertTrue($response->getStatusCode() == 200);

        // check output json
        $content = json_decode($response->getContent(), true);
        $this->assertTrue($content['status'] == 1);

    }

    function test_get_tag_list_throw_exception() {
        $this->tag->expects($this->once())->method('getTags')->will( $this->throwException(new \Exception) );

        $request = $this->createMock('\Illuminate\Http\Request');
        $response = $this->tagCtrl->getTags($request);

        $contentArr = json_decode($response->getContent(), true);

        // check return status == 0 / error
        $this->assertTrue($contentArr['status'] == 0);
    }


}
