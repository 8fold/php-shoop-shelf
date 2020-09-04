<?php

namespace Eightfold\Shoop\FluentTypes\Contracts;

interface DebuggableImp
{
    public function __debugInfo(): array
    {
        return [
            "main" => $this->main;
            "arguments" => $this->args()
        ];
    }
}
