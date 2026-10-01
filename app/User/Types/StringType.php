<?php

declare(strict_types=1);

namespace App\User\Types;

use App\User\Contracts\TypeHandler;
use InvalidArgumentException;

final class StringType implements TypeHandler
{
    private const CONTROL_CHARS_PATTERN = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/';

    public function handle(mixed $value, array $rule): string
    {
        $isRequired = $rule['required'] ?? true;

        if ($this->isBlank($value)) {
            return $this->handleEmpty($isRequired);
        }

        $text = $this->toText($value);
        $text = $this->removeControlChars($text);

        if ($rule['strip_tags'] ?? false) {
            $text = strip_tags($text);
        }

        if ($rule['collapse_spaces'] ?? false) {
            $text = $this->collapseSpaces($text);
        }

        $text = trim($text);

        if ($text === '') {
            return $this->handleEmpty($isRequired);
        }

        $this->assertLength($text, $rule['min'] ?? null, $rule['max'] ?? null);

        return $text;
    }

    private function isBlank(mixed $value): bool
    {
        return $value === null
            || (is_string($value) && trim($value) === '');
    }

    private function handleEmpty(bool $isRequired): string
    {
        if ($isRequired) {
            throw new InvalidArgumentException('Vui lòng nhập ký tự, không được để trống');
        }

        return '';
    }

    private function toText(mixed $value): string
    {
        if (!is_scalar($value)) {
            throw new InvalidArgumentException('Phải là chuỗi');
        }

        $text = (string) $value;

        if (!mb_check_encoding($text, 'UTF-8')) {
            throw new InvalidArgumentException('Chuỗi không phải UTF-8 hợp lệ');
        }

        return $text;
    }

    private function removeControlChars(string $text): string
    {
        return preg_replace(self::CONTROL_CHARS_PATTERN, '', $text);
    }

    private function collapseSpaces(string $text): string
    {
        return preg_replace('/\s+/u', ' ', $text);
    }

    private function assertLength(string $text, ?int $min, ?int $max): void
    {
        $length = mb_strlen($text);

        if ($min !== null && $length < $min) {
            throw new InvalidArgumentException("Tối thiểu {$min} ký tự");
        }

        if ($max !== null && $length > $max) {
            throw new InvalidArgumentException("Tối đa {$max} ký tự");
        }
    }
}