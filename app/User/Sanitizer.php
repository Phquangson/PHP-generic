<?php

declare(strict_types=1);

namespace App\User;

use App\Exceptions\ValidationException;
use App\User\Contracts\TypeHandler;
use App\User\Types\BoolType;
use App\User\Types\DateType;
use App\User\Types\EmailType;
use App\User\Types\FloatType;
use App\User\Types\IntType;
use App\User\Types\JsonType;
use App\User\Types\StringType;
use InvalidArgumentException;

final class User
{
    private const DEFAULT_TYPE = 'string';
    private const MESSAGE_REQUIRED = 'Bắt buộc phải có';
    private const MESSAGE_NOT_ALLOWED = 'Giá trị không nằm trong danh sách cho phép';

    /** @var array<string, TypeHandler> */
    private array $handlers = [];

    public function __construct()
    {
        $this->registerDefaultHandlers();
    }

    public function register(string $typeName, TypeHandler $handler): self
    {
        $this->handlers[$typeName] = $handler;

        return $this;
    }

    public function sanitizeValue(mixed $value, array $rule): mixed
    {
        $typeName = $rule['type'] ?? self::DEFAULT_TYPE;
        $handler = $this->resolveHandler($typeName);

        $value = $this->trimIfNeeded($value, $rule);
        $result = $handler->handle($value, $rule);

        $this->assertAllowed($result, $rule);

        return $result;
    }

    public function sanitize(array $data, array $rules): array
    {
        $cleanData = [];
        $errors = [];

        foreach ($rules as $field => $rule) {
            $rule = $this->normalizeRule($rule);
            $value = $this->nullIfBlank($data[$field] ?? null);

            if ($value === null) {
                if (array_key_exists('default', $rule)) {
                    $cleanData[$field] = $rule['default'];
                } elseif ($rule['required'] ?? false) {
                    $errors[$field] = self::MESSAGE_REQUIRED;
                } elseif (array_key_exists($field, $data)) {
                    $cleanData[$field] = null;
                }
                continue;
            }

            try {
                $cleanData[$field] = $this->sanitizeValue($value, $rule);
            } catch (InvalidArgumentException $exception) {
                $errors[$field] = $exception->getMessage();
            }
        }

        $this->throwIfHasErrors($errors);

        return $cleanData;
    }

    public function sanitizeMany(array $rows, array $rules): array
    {
        $cleanRows = [];
        $errorsByRow = [];

        foreach ($rows as $index => $row) {
            try {
                $cleanRows[$index] = $this->sanitize($row, $rules);
            } catch (ValidationException $exception) {
                $errorsByRow[$index] = $exception->errors();
            }
        }

        $this->throwIfHasErrors($errorsByRow);

        return $cleanRows;
    }

    private function registerDefaultHandlers(): void
    {
        $this->register('string', new StringType());
        $this->register('int', new IntType());
        $this->register('float', new FloatType());
        $this->register('bool', new BoolType());
        $this->register('email', new EmailType());
        $this->register('date', new DateType());
        $this->register('json', new JsonType());
    }

    private function resolveHandler(string $typeName): TypeHandler
    {
        if (!isset($this->handlers[$typeName])) {
            throw new InvalidArgumentException("Không hỗ trợ type '{$typeName}'");
        }

        return $this->handlers[$typeName];
    }

    private function normalizeRule(array|string $rule): array
    {
        return is_string($rule) ? ['type' => $rule] : $rule;
    }

    private function nullIfBlank(mixed $value): mixed
    {
        return is_string($value) && trim($value) === '' ? null : $value;
    }

    private function trimIfNeeded(mixed $value, array $rule): mixed
    {
        if (is_string($value) && ($rule['trim'] ?? true)) {
            return trim($value);
        }

        return $value;
    }

    private function assertAllowed(mixed $result, array $rule): void
    {
        if (isset($rule['in']) && !in_array($result, $rule['in'], true)) {
            throw new InvalidArgumentException(self::MESSAGE_NOT_ALLOWED);
        }
    }

    private function throwIfHasErrors(array $errors): void
    {
        if ($errors) {
            throw new ValidationException($errors);
        }
    }
}