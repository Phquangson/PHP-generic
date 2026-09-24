<?php

declare(strict_types=1);

namespace App\Sanitizer\Types;

use App\Sanitizer\Contracts\TypeHandler;
use InvalidArgumentException;

abstract class NumberType implements TypeHandler
{
    protected function checkRange(int|float $number, array $rule): int|float
    {
        if (isset($rule['min']) && $number < $rule['min']) {
            throw new InvalidArgumentException("Giá trị tối thiểu là {$rule['min']}");
        }
        if (isset($rule['max']) && $number > $rule['max']) {
            throw new InvalidArgumentException("Giá trị tối đa là {$rule['max']}");
        }

        return $number;
    }
}