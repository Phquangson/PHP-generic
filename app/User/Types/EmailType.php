<?php

declare(strict_types=1);

namespace App\User\Types;

use App\User\Contracts\TypeHandler;
use InvalidArgumentException;

final class EmailType implements TypeHandler
{
    public function handle(mixed $value, array $rule): string
    {
        $isRequired = $rule['required'] ?? true;

        if ($this->isBlank($value)) {
            return $this->handleEmpty($isRequired);
        }

        return $this->normalizeEmail($value);
    }

    private function isBlank(mixed $value): bool
    {
        return $value === null || (is_string($value) && trim($value) === '');
    }

    private function handleEmpty(bool $isRequired): string
    {
        if ($isRequired) {
            throw new InvalidArgumentException('Vui lòng nhập email, không được để trống');
        }

        return '';
    }

    private function normalizeEmail(mixed $value): string
    {
        $email = filter_var($value, FILTER_VALIDATE_EMAIL);

        if ($email === false) {
            throw new InvalidArgumentException('Email không hợp lệ');
        }

        return mb_strtolower($email);
    }
}