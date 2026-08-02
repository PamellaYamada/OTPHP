<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\Cache;

final class MemoryCache implements CacheInterface
{
    /** @var array<string, array{value: mixed, expires_at: int}> */
    private static array $store = [];

    public function set(string $key, mixed $value, int $ttlSeconds): bool
    {
        self::$store[$key] = [
            'value' => $value,
            'expires_at' => time() + $ttlSeconds,
        ];

        return true;
    }

    public function get(string $key): mixed
    {
        if (! $this->has($key)) {
            return null;
        }

        return self::$store[$key]['value'];
    }

    public function has(string $key): bool
    {
        if (! isset(self::$store[$key])) {
            return false;
        }

        if (time() > self::$store[$key]['expires_at']) {
            unset(self::$store[$key]);

            return false;
        }

        return true;
    }

    public function delete(string $key): bool
    {
        unset(self::$store[$key]);

        return true;
    }

    public static function flush(): void
    {
        self::$store = [];
    }
}
