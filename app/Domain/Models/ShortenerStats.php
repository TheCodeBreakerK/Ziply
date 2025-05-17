<?php

namespace App\Domain\Models;

use Predis\Client as RedisClient;

class ShortenerStats
{
    private const string ACCESS_PREFIX = 'stats:';

    public function __construct(private readonly RedisClient $redis) {}

    public function incrementAccessCount(string $key): void
    {
        $this->redis->incr(self::ACCESS_PREFIX . $key);
    }

    public function getAccessCount(string $key): int
    {
        return $this->redis->get(self::ACCESS_PREFIX . $key);
    }
}