<?php

namespace Eightfold\Shoop\Filter\Contracts;

interface Divisible extends PipeSubtractable
{
    public function divide(
        $start = 0,
        bool $includeEmpties = true,
        int $limit = PHP_INT_MAX
    )
    {
    }
}
