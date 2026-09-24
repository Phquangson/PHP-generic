<?php

declare(strict_types=1);

namespace App\Sanitizer\Contracts;

interface TypeHandler
{
    public function handle(mixed $value, array $rule): mixed;
}