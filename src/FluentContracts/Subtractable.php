<?php

namespace Eightfold\Shoop\FluentTypes\Contracts;

use Eightfold\Shoop\FilterContracts\Subtractable as PipeSubtractible;

interface Subtractable extends PipeSubtractible
{
    public function minusEmpties();

    public function minusFirst($length = 1);

    public function minusLast($length = 1);
}
