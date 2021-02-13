<?php

namespace Eightfold\Json;

class Write
{
    private $original = [];

    private $draft = [];

    private $merged = [];

    private $encoded = '';

    static public function writeJson(array $original = [], array $draft = [])
    {
        return new Write($original, $draft);
    }

    public function __construct(array $original = [], array $draft = [])
    {
        $this->original = $original;
        $this->draft = $draft;
    }

    public function addMember(string $memberName, $memberValue): Write
    {
        $this->draft[$memberName] = $memberValue;
        return $this;
    }

    public function compile(bool $returnResult = false)
    {
        $this->merged = array_merge($this->original, $this->draft);
        if ($returnResult) {
            return $this->merged;
        }
        return $this;
    }

    public function encode(bool $returnResult = false)
    {
        $this->encoded = json_encode($this->compile(true), JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
        if ($returnResult) {
            return $this->encoded;
        }
        return $this;
    }

    public function save(string $dest): bool
    {
        file_put_contents($dest, $this->encode());
        return file_exists($dest);
    }
}
