<?php

namespace Eightfold\ApiKit;

use GuzzleHttp\{
	Client,
	RequestOptions,
	Exception\RequestException,
	Exception\ClientException
};

use Psr\Http\Message\ResponseInterface;

use Eightfold\Shoop\Shoop;

class ApiKit
{
	private $baseUri = "";

	private $endpoint = "";

	private $config = [];

	private $guzzle;

	static public function connect(string $baseUri, array $config = []): ApiKit
	{
		return new ApiKit($baseUri, $config);
	}

	public function __construct(string $baseUri, array $config = [])
	{
		$default = [];
        $default['headers']['User-Agent'] = '8fold\APIKit ' . \GuzzleHttp\default_user_agent();
        $default['headers']['Content-Type'] = 'application/json';

        $this->config = array_merge($default, $config);

        $this->baseUri = $baseUri;
	}

	private function guzzle(): Client
	{
		if ($this->guzzle !== null) {
			return $this->guzzle;
		}
		$this->guzzle = new Client($this->config);
		return $this->guzzle();
	}

	private function endpoint(): string
	{
		return $this->baseUri . $this->endpoint;
	}

	public function connected(): bool
	{
        $response = $this->guzzle()->get($this->endpoint());
        return ($response->getStatusCode() === 200);
	}

	public function call(string $endpoint)
	{
		$this->endpoint = $endpoint;
		return $this;
	}

	public function get(): Response
	{
		try {
			$response = $this->guzzle()->get($this->endpoint());

		} catch (RequestException $e) {
			$response = $e;

		}
		return new Response($response);
	}

	public function post(array $postData)
	{
		try {
			$response = $this->guzzle()->request("POST", $this->endpoint(), [
				RequestOptions::JSON => $postData
			]);
			
		} catch (RequestException $e) {
			$response = $e;

		}
		return new Response($response);
	}
}