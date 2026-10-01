<?php

declare(strict_types=1);

namespace App\User\Types;

use InvalidArgumentException;

final class IntType extends NumberType
{
    public function handle(mixed $value, array $rule): int
    {
        $isRequired = $rule['required'] ?? true;

        if ($this->isBlank($value)) {
            return $this->handleEmpty($isRequired);
        }

        $number = $this->toInt($value);

        return $this->checkRange($number, $rule);
    }

    private function isBlank(mixed $value): bool
    {
        return $value === null
            || (is_string($value) && trim($value) === '');
    }

    private function handleEmpty(bool $isRequired): int
    {
        if ($isRequired) {
            throw new InvalidArgumentException('Vui lòng nhập số, không được để trống');
        }

        return 0;
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