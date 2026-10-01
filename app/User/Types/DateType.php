<?php

declare(strict_types=1);

namespace App\User\Types;

use App\User\Contracts\TypeHandler;
use DateTimeImmutable;
use InvalidArgumentException;

final class DateType implements TypeHandler
{
    private const DEFAULT_FORMAT = 'Y-m-d';

    public function handle(mixed $value, array $rule): string
    {
        $isRequired = $rule['required'] ?? true;

        if ($this->isBlank($value)) {
            return $this->handleEmpty($isRequired);
        }

        $inputFormat  = $rule['format'] ?? self::DEFAULT_FORMAT;
        $outputFormat = $rule['output'] ?? $inputFormat;

        $date = $this->parseDate($value, $inputFormat);

        return $date->format($outputFormat);
    }

    private function isBlank(mixed $value): bool
    {
        return $value === null
            || (is_string($value) && trim($value) === '');
    }

    private function handleEmpty(bool $isRequired): string
    {
        if ($isRequired) {
            throw new InvalidArgumentException('Vui lòng chọn ngày, không được để trống');
        }

        return '';
    }

    private function parseDate(mixed $value, string $format): DateTimeImmutable
    {
        if (!is_scalar($value)) {
            throw new InvalidArgumentException("Ngày không đúng định dạng {$format}");
        }

        $date = DateTimeImmutable::createFromFormat('!' . $format, (string) $value);

        if ($date === false || $this->hasParseErrors()) {
            throw new InvalidArgumentException("Ngày không đúng định dạng {$format}");
        }

        return $date;
    }

    private function hasParseErrors(): bool
    {
        $errors = DateTimeImmutable::getLastErrors();

        return $errors !== false
            && ($errors['warning_count'] > 0 || $errors['error_count'] > 0);
    }
}