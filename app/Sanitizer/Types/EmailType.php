<?php

declare(strict_types=1);

namespace App\Sanitizer\Types;

use App\Sanitizer\Contracts\TypeHandler;
use InvalidArgumentException;

final class EmailType implements TypeHandler
{
    public function handle(mixed $value, array $rule): string
    {
        $email = filter_var($value, FILTER_VALIDATE_EMAIL);

        if ($email === false) {
            throw new InvalidArgumentException('Email không hợp lệ');
        }

        return mb_strtolower($email);
    }
}