<?php

declare(strict_types=1);

namespace App\Sanitizer\Types;

use App\Sanitizer\Contracts\TypeHandler;
use InvalidArgumentException;

final class StringType implements TypeHandler
{
    public function handle(mixed $value, array $rule): string
    {
        if (!is_scalar($value)) {
            throw new InvalidArgumentException('Phải là chuỗi');
        }

        $s = (string) $value;

        if (!mb_check_encoding($s, 'UTF-8')) {
            throw new InvalidArgumentException('Chuỗi không phải UTF-8 hợp lệ');
        }

        $s = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $s);

        if ($rule['strip_tags'] ?? false) {
            $s = strip_tags($s);
        }

        if ($rule['collapse_spaces'] ?? false) {
            $s = preg_replace('/\s+/u', ' ', $s);
        }

        $s = trim($s);
        $len = mb_strlen($s);

        if (isset($rule['min']) && $len < $rule['min']) {
            throw new InvalidArgumentException("Tối thiểu {$rule['min']} ký tự");
        }
        if (isset($rule['max']) && $len > $rule['max']) {
            throw new InvalidArgumentException("Tối đa {$rule['max']} ký tự");
        }

        return $s;
    }
}