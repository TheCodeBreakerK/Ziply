<?php

namespace App\models;

use Predis\Client as RedisClient;
use RedisException;

class RedisConnection
{
    private static ?RedisConnection $instance = null;
    private RedisClient $redis;

    private function __construct()
    {
        $redisConfig = require_once __DIR__ . "/../../config/redis.php";
        try {
            $this->redis = new RedisClient(...$redisConfig);
        } catch (RedisException $e) {
            throw new RedisException('Redis connection failure: ' . $e->getMessage());
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
