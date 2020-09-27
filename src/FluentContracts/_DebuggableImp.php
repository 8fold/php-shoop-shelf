<?php

namespace Eightfold\Shoop\FluentTypes\Contracts;

interface _DebuggableImp
{
    public function __debugInfo(): array
    {
        return [
            "main" => $this->main;
            "arguments" => $this->args()
        ];
    }
}
