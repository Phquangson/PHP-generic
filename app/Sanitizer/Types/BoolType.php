<?php

declare(strict_types=1);

namespace App\Sanitizer\Types;

use App\Sanitizer\Contracts\TypeHandler;
use InvalidArgumentException;

final class BoolType implements TypeHandler
{
    public function handle(mixed $value, array $rule): bool
    {
        $bool = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($bool === null) {
            throw new InvalidArgumentException('Phải là true/false');
        }

        return $bool;
    }
}