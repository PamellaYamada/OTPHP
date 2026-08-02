<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\Security;

use PamellaYamada\OTPHP\Cache\CacheInterface;
use PamellaYamada\OTPHP\Cache\MemoryCache;

final class StrictRateLimiter
{
    private CacheInterface $cache;

    public function __construct(?CacheInterface $cache = null)
    {
        $this->cache = $cache ?? new MemoryCache();
    }

    public function isRateLimited(string $identifier): bool
    {
        $key = 'otphp_ratelimit_' . md5($identifier);
        return $this->cache->has($key);
    }

    public function hit(string $identifier, int $decaySeconds = 60): void
    {
        $key = 'otphp_ratelimit_' . md5($identifier);
        $this->cache->set($key, true, $decaySeconds);
    }
}
