<?php

namespace App\Infrastructure;

use Predis\Client as RedisClient;
use App\Infrastructure\Exceptions\RedisConnectionException;

class RedisConnection
{
    private static ?RedisConnection $instance = null;
    private RedisClient $redis;

    private function __construct()
    {
        $redisConfig = require_once __DIR__ . "/../../config/redis.php";
        try {
            $this->redis = new RedisClient(...$redisConfig);
        } catch (RedisConnectionException $e) {
            throw new RedisConnectionException('Redis connection failure: ' . $e->getMessage());
        }
    }

    public static function getInstance(): RedisClient
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->redis;
    }

    private function __clone() {}
    private function __wakeup() {}
}
