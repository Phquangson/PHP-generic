<?php

declare(strict_types=1);

namespace App\Database;

use InvalidArgumentException;

final class InsertBuilder
{
    public static function build(string $table, array $data): array
    {
        if ($data === []) {
            throw new InvalidArgumentException('Không có dữ liệu để insert');
        }

        $columns = array_map([self::class, 'quote'], array_keys($data));
        $placeholders = array_map(fn($key) => ':' . $key, array_keys($data));

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            self::quote($table),
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        return [$sql, $data];
    }

    private static function quote(string|int $name): string
    {
        $name = (string) $name;

        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name)) {
            throw new InvalidArgumentException("Tên bảng/cột không hợp lệ: $name");
        }

        return "`$name`";
    }
}