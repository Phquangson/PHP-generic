<?php

declare(strict_types=1);

namespace App\Sanitizer\Types;

use InvalidArgumentException;

final class IntType extends NumberType
{
    public function handle(mixed $value, array $rule): int
    {
        $number = filter_var($value, FILTER_VALIDATE_INT);

        if ($number === false) {
            throw new InvalidArgumentException('Phải là số nguyên');
        }

        return $this->checkRange($number, $rule);
    }
}