<?php

declare(strict_types=1);

namespace App\User\Types;

use App\User\Contracts\TypeHandler;
use InvalidArgumentException;

abstract class NumberType implements TypeHandler
{
    protected function isBlank(mixed $value): bool
    {
        return $value === null
            || (is_string($value) && trim($value) === '');
    }

    protected function handleEmpty(bool $isRequired): int|float
    {
        if ($isRequired) {
            throw new InvalidArgumentException('Vui lòng nhập số, không được để trống');
        }

        return 0;
    }

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