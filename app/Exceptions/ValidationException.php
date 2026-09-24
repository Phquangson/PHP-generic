<?php

declare(strict_types=1);

namespace App\Exceptions;

use InvalidArgumentException;

final class ValidationException extends InvalidArgumentException
{
    public function __construct(private array $errors)
    {
        parent::__construct('Dữ liệu không hợp lệ: ' . json_encode($errors, JSON_UNESCAPED_UNICODE));
    }

    public function errors(): array
    {
        return $this->errors;
    }
}