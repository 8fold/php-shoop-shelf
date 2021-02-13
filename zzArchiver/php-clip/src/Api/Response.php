<?php

namespace Eightfold\Clip\Api;

use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Exception\RequestException;

use Eightfold\Shoop\Shoop;

class Response
{
    private $guzzleResponse;

    private $content = "";

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
        $notGuzzle = Shoop::bool(is_a($guzzleResponse, GuzzleResponse::class))->toggle()->unfold();
        $notException = Shoop::bool(is_a($guzzleResponse, RequestException::class))->toggle()->unfold();
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

    public function statusIs(int $compare): bool
    {
        return Shoop::int($this->status())->isSameUnfolded($compare);
    }

    public function statusIsNot(int $compare): bool
    {
        return Shoop::bool($this->statusIs($compare))->toggleUnfolded();
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
        if (Shoop::string($this->content)->count()->is(0)->unfold()) {
            $this->content = $this->guzzleResponse->getBody()->getContents();
        }
        return $this->content;
    }

    public function decodedContent(): array
    {
        return json_decode($this->content(), true);
    }

    public function json(): Json
    {
        return Json::fromString($this->content());
    }

    public function count(): int
    {
        return count($this->decodedContent());
    }
}
