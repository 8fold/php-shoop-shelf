<?php

namespace Eightfold\ApiKit;

use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Exception\RequestException;

use Eightfold\Shoop\Shoop;

class Response
{
	private $guzzleResponse;

	public function __construct($guzzleResponse)
	{
		$notException = $this->checkConstructorClassOrTriggerError($guzzleResponse);
		$this->guzzleResponse = ($notException)
			? $guzzleResponse
			: $guzzleResponse->getResponse();
	}

	public function __call($name, $args)
	{
		$decoded = json_decode($this->content(), true);
		return $decoded[$name];
	}

	private function checkConstructorClassOrTriggerError($guzzleResponse): bool
	{
		$notGuzzle = Shoop::bool(is_a($guzzleResponse, GuzzleResponse::class))->toggle()->unwrap();
		$notException = Shoop::bool(is_a($guzzleResponse, RequestException::class))->toggle()->unwrap();
		if ($notGuzzle && $notException) {
	        trigger_error(
	            "Argument 1 passed to ApiKit Response constructor must be of type GuzzleResponse or RequestException",
	            E_USER_ERROR
	        );
		}
		return $notException;
	}

	public function status(): int
	{
		return $this->guzzleResponse->getStatusCode();
	}

	public function reason(): string
	{
		return $this->guzzleResponse->getReasonPhrase();
	}

	public function guzzleResponse()
	{
		return $this->guzzleResponse;
	}

	public function content(): string
	{
		return $this->guzzleResponse->getBody()->getContents();
	}

	public function decodedContent(): array
	{
		return json_decode($this->content(), true);
	}

	public function count(): int
	{
		return count($this->decodedContent());
	}
}