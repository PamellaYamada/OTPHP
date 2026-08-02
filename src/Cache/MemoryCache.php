<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\Cache;

final class MemoryCache implements CacheInterface
{
    /** @var array<string, int> */
    private static array $store = [];

    public function set(string $key, mixed $value, int $ttlSeconds): bool
    {
        self::$store[$key] = time() + $ttlSeconds;
        return true;
    }

    public function has(string $key): bool
    {
        if (!isset(self::$store[$key])) {
            return false;
        }

        if (time() > self::$store[$key]) {
            unset(self::$store[$key]);
            return false;
        }

        return true;
    }

    public static function flush(): void
    {
        self::$store = [];
    }
}
