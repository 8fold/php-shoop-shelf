<?php

namespace Eightfold\APIKit\Tests;

use PHPUnit\Framework\TestCase;

use GuzzleHttp\Client;

use Eightfold\ApiKit\{
	ApiKit,
	Response
};

class MainTest extends TestCase
{
	private $base =  "https://my-json-server.typicode.com/8fold/json-tester";

	public function testCanGetPosts()
	{
		$result = APIKit::connect($this->base)->call("/posts")->get();
		$this->assertNotNull($result);
		$this->assertEquals(200, $result->status());
		$this->assertEquals(3, $result->count());

		$result = ApiKit::connect($this->base)->call("/pasts")->get();
		$this->assertEquals(404, $result->status());
	}

	public function testCanInitializeResponse()
	{
		$result = APIKit::connect($this->base)->call("/posts")->get();
		$this->assertTrue(is_a($result, Response::class));

		$result = APIKit::connect($this->base)->call("/pasts")->get();
		$this->assertTrue(is_a($result, Response::class));
	}

	public function testCanPostPosts()
	{
		$result = ApiKit::connect($this->base)->call("/posts")->post(
			[
				"id" => 4,
				"title" => "Post 4"
			]
		);
		$this->assertEquals(201, $result->status());
	}
}