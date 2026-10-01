<?php

declare(strict_types=1);

namespace App\User\Types;

use InvalidArgumentException;

final class IntType extends NumberType
{
    public function handle(mixed $value, array $rule): int
    {
        return $this->checkRange($this->toInt($value), $rule);
    }

    private function toInt(mixed $value): int
    {
        $number = filter_var($value, FILTER_VALIDATE_INT);

        if ($number === false) {
            throw new InvalidArgumentException('Phải là số nguyên');
        }

        return $number;
    }
}