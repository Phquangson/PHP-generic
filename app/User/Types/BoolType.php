<?php

declare(strict_types=1);

namespace App\User\Types;

use App\User\Contracts\TypeHandler;
use InvalidArgumentException;

final class BoolType implements TypeHandler
{
    public function handle(mixed $value, array $rule): bool
    {
        if ($this->isBlank($value) && ($rule['required'] ?? true)) {
            throw new InvalidArgumentException('Vui lòng chọn giá trị, không được để trống');
        }

        return $this->toBool($value);
    }

    private function isBlank(mixed $value): bool
    {
        return $value === null
            || (is_string($value) && trim($value) === '');
    }

    private function toBool(mixed $value): bool
    {
        $result = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($result === null) {
            throw new InvalidArgumentException('Phải là true/false');
        }

        return $result;
    }
}