<?php

declare(strict_types=1);

namespace App\User\Types;

use InvalidArgumentException;

final class FloatType extends NumberType
{
    public function handle(mixed $value, array $rule): float
    {
        $isRequired = $rule['required'] ?? true;

        if ($this->isBlank($value)) {
            return $this->handleEmpty($isRequired);
        }

        $number = $this->toFloat($value);

        return $this->checkRange($number, $rule);
    }

    private function isBlank(mixed $value): bool
    {
        return $value === null
            || (is_string($value) && trim($value) === '');
    }

    private function handleEmpty(bool $isRequired): float
    {
        if ($isRequired) {
            throw new InvalidArgumentException('Vui lòng nhập số, không được để trống');
        }

        return 0.0;
    }

    private function toFloat(mixed $value): float
    {
        $number = filter_var($value, FILTER_VALIDATE_FLOAT);

        if ($number === false) {
            throw new InvalidArgumentException('Phải là số thực');
        }

        return $number;
    }
}