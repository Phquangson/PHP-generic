<?php

declare(strict_types=1);

namespace App\Sanitizer\Types;

use App\Sanitizer\Contracts\TypeHandler;
use DateTimeImmutable;
use InvalidArgumentException;

final class DateType implements TypeHandler
{
    public function handle(mixed $value, array $rule): string
    {
        $format = $rule['format'] ?? 'Y-m-d';

        $date = is_scalar($value)
            ? DateTimeImmutable::createFromFormat('!' . $format, (string) $value)
            : false;

        $errors = DateTimeImmutable::getLastErrors();

        if ($date === false || ($errors && ($errors['warning_count'] || $errors['error_count']))) {
            throw new InvalidArgumentException("Ngày không đúng định dạng $format");
        }

        return $date->format($rule['output'] ?? $format);
    }
}