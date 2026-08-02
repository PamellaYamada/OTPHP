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
        $this->cache = $cache ?? new MemoryCache;
    }

    public function attempts(string $identifier): int
    {
        $key = $this->getCacheKey($identifier);
        $val = $this->cache->get($key);

        return is_numeric($val) ? (int) $val : 0;
    }

    public function hit(string $identifier, int $decaySeconds = 60): int
    {
        $key = $this->getCacheKey($identifier);
        $attempts = $this->attempts($identifier) + 1;
        $this->cache->set($key, $attempts, $decaySeconds);

        return $attempts;
    }

    public function tooManyAttempts(string $identifier, int $maxAttempts = 5): bool
    {
        return $this->attempts($identifier) >= $maxAttempts;
    }

    public function reset(string $identifier): void
    {
        $key = $this->getCacheKey($identifier);
        $this->cache->delete($key);
    }

    private function getCacheKey(string $identifier): string
    {
        return 'otphp_ratelimit_'.hash('sha256', $identifier);
    }
}
