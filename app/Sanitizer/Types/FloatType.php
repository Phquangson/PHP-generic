<?php

declare(strict_types=1);

namespace App\Sanitizer\Types;

use InvalidArgumentException;

final class FloatType extends NumberType
{
    public function handle(mixed $value, array $rule): float
    {
        $number = filter_var($value, FILTER_VALIDATE_FLOAT);

        if ($number === false) {
            throw new InvalidArgumentException('Phải là số thực');
        }

        return $this->checkRange($number, $rule);
    }
}