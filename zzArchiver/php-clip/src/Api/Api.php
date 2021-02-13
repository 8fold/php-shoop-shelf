<?php

namespace Eightfold\Clip\Api;

use GuzzleHttp\{
    Client,
    RequestOptions,
    Exception\RequestException,
    Exception\ClientException
};

use Psr\Http\Message\ResponseInterface;

use Eightfold\Shoop\{Shoop, ESJson};

class Api
{
    private $baseUri = "";

    private $endpoint = "";

    private $postData;

    private $config = [];

    private $guzzle;

    static public function connect(string $baseUri, array $config = []): Self
    {
        return new Api($baseUri, $config);
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
        if ($this->guzzle == null) {
            $this->guzzle = new Client($this->config);
        }
        return $this->guzzle;
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

    public function call(string $endpoint, ESJson $postData = null)
    {
        $this->postData = $postData;
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

    public function post(string $method = "post")
    {
        $method = Shoop::string($method)->uppercaseUnfolded();
        try {
            $response = $this->guzzle()->request($method, $this->endpoint(), [
                RequestOptions::JSON => $this->postData
            ]);

        } catch (RequestException $e) {
            $response = $e;

        }
        return new Response($response);
    }
}
