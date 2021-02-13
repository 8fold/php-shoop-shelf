<?php

namespace Eightfold\Clip\Tests;

use PHPUnit\Framework\TestCase;

use GuzzleHttp\Client;

use Eightfold\Shoop\Shoop;

use Eightfold\Clip\Api\{
    Api,
    Response
};

class MainTest extends TestCase
{
    private $base =  "https://my-json-server.typicode.com/8fold/json-tester";

    public function testCanGetPosts()
    {
        $result = Api::connect($this->base)->call("/posts")->get();
        $this->assertNotNull($result);
        $this->assertEquals(200, $result->status());
        $this->assertEquals(3, $result->count());

        $result = Api::connect($this->base)->call("/pasts")->get();
        $this->assertEquals(404, $result->status());
    }

    public function testCanInitializeResponse()
    {
        $result = Api::connect($this->base)->call("/posts")->get();
        $this->assertTrue(is_a($result, Response::class));

        $result = Api::connect($this->base)->call("/pasts")->get();
        $this->assertTrue(is_a($result, Response::class));
    }

    public function testCanPostPosts()
    {
        // $data = ["posts" => [
        //     ["id" => 4, "title" => "Post 4"]
        //     ["id" => 4, "title" => "Post 4"]
        // ]];
        // $j = json_encode($data);
        // die(var_dump($j));
        // $json = Shoop::json($j);
        // $result = Api::connect($this->base)->call("/posts", $json)
        // ->post();
        // $this->assertEquals(201, $result->status());
    }
}
