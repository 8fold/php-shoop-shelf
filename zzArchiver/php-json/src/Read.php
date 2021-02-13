<?php

namespace Eightfold\Json;

class Read
{
	private $decoded = [];
	private $currentNode = '';

	static public function fromPath(string $path): Read 
	{
		$json = file_get_contents($path);
		return Read::fromString($json);
	}

	static public function fromString(string $json): Read 
	{
		return new Read($json);
	}

	public function __construct(string $json) 
	{
		$this->decoded = json_decode($json);
		$this->currentNode = $this->decoded;
	}

	public function __call(string $method, $args) 
	{
		return $this->getKey($method);
	}

	public function getKey(string $key): Read 
	{
		$this->currentNode = $this->currentNode->{$key};
		return $this;
	}

	public function fetch(string $key = '') 
	{
		if (strlen($key) > 0) {
			$this->getKey($key);
		}
		return $this->currentNode;
	}

	public function fetchIndex(int $index, string $key = '') 
	{
		if (strlen($key) > 0) {
			$this->getKey($key);
		}
		return $this->currentNode[$index];
	}
}
