<?php

declare(strict_types=1);

namespace App\Sanitizer\Types;

use App\Sanitizer\Contracts\TypeHandler;
use InvalidArgumentException;

final class JsonType implements TypeHandler
{
    public function handle(mixed $value, array $rule): string
    {
        $json = is_string($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE);

        if ($json === false) {
            throw new InvalidArgumentException('JSON không hợp lệ');
        }

        json_decode($json);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('JSON không hợp lệ');
        }

        return $json;
    }
}