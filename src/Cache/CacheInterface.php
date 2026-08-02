<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\Cache;

interface CacheInterface
{
    public function set(string $key, mixed $value, int $ttlSeconds): bool;
    public function has(string $key): bool;
}
