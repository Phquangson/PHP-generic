<?php

declare(strict_types=1);

namespace App\Sanitizer;

use App\Exceptions\ValidationException;
use App\Sanitizer\Contracts\TypeHandler;
use App\Sanitizer\Types\BoolType;
use App\Sanitizer\Types\DateType;
use App\Sanitizer\Types\EmailType;
use App\Sanitizer\Types\FloatType;
use App\Sanitizer\Types\IntType;
use App\Sanitizer\Types\JsonType;
use App\Sanitizer\Types\StringType;
use InvalidArgumentException;

final class Sanitizer
{
    /** @var array<string, TypeHandler> */
    private array $types = [];

    public function __construct()
    {
        $this->register('string', new StringType());
        $this->register('int', new IntType());
        $this->register('float', new FloatType());
        $this->register('bool', new BoolType());
        $this->register('email', new EmailType());
        $this->register('date', new DateType());
        $this->register('json', new JsonType());
    }

    public function register(string $name, TypeHandler $handler): self
    {
        $this->types[$name] = $handler;

        return $this;
    }

    public function sanitizeValue(mixed $value, array $rule): mixed
    {
        $type = $rule['type'] ?? 'string';

        if (!isset($this->types[$type])) {
            throw new InvalidArgumentException("Không hỗ trợ type '$type'");
        }

        if (is_string($value) && ($rule['trim'] ?? true)) {
            $value = trim($value);
        }

        $result = $this->types[$type]->handle($value, $rule);

        if (isset($rule['in']) && !in_array($result, $rule['in'], true)) {
            throw new InvalidArgumentException('Giá trị không nằm trong danh sách cho phép');
        }

        return $result;
    }

    public function sanitize(array $data, array $rules): array
    {
        $clean = [];
        $errors = [];

        foreach ($rules as $field => $rule) {
            $rule = is_string($rule) ? ['type' => $rule] : $rule;
            $exists = array_key_exists($field, $data);
            $value = $data[$field] ?? null;

            if (is_string($value) && trim($value) === '') {
                $value = null;
            }

            if ($value === null) {
                if (array_key_exists('default', $rule)) {
                    $clean[$field] = $rule['default'];
                } elseif ($rule['required'] ?? false) {
                    $errors[$field] = 'Bắt buộc phải có';
                } elseif ($exists) {
                    $clean[$field] = null;
                }
                continue;
            }

            try {
                $clean[$field] = $this->sanitizeValue($value, $rule);
            } catch (InvalidArgumentException $e) {
                $errors[$field] = $e->getMessage();
            }
        }

        if ($errors) {
            throw new ValidationException($errors);
        }

        return $clean;
    }

    public function sanitizeMany(array $rows, array $rules): array
    {
        $result = [];
        $errors = [];

        foreach ($rows as $i => $row) {
            try {
                $result[$i] = $this->sanitize($row, $rules);
            } catch (ValidationException $e) {
                $errors[$i] = $e->errors();
            }
        }

        if ($errors) {
            throw new ValidationException($errors);
        }

        return $result;
    }
}