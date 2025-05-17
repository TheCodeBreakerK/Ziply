<?php

namespace App\Domain\Models;

use Predis\Client as RedisClient;
use App\Domain\ValueObjects\Url;
use App\Domain\Exceptions\UniqueKeyGenerationException;

class Shortener
{
    private const string REDIS_KEY_PREFIX = 'url:';
    private const int    KEY_LENGTH  = 6;
    private const int    MAX_ATTEMPTS = 5;
    private const int    DEFAULT_EXPIRATION_TIME = 86400;

    public function __construct(private readonly RedisClient $redis) {}

    public function shortenUrl(Url $url): string
    {
        $key = $this->generateUniqueKey();
        $this->redis->set(
            key:              self::REDIS_KEY_PREFIX . $key,
            value:            strval($url),
            expireResolution: 'EX',
            expireTTL:        self::DEFAULT_EXPIRATION_TIME
        );

        return $key;
    }

    public function getUrl(string $key): ?Url
    {
        $url = $this->redis->get(self::REDIS_KEY_PREFIX . $key);
        if (empty($url)) {
            return null;
        }

        return new Url($url);
    }

    public function getAllUrls(): array
    {
        $keys = $this->redis->keys(self::REDIS_KEY_PREFIX . '*');

        $urls = [];
        foreach ($keys as $key) {
            $url = $this->redis->get(self::REDIS_KEY_PREFIX, '', $key);
            $urls = [
                'key' => $key,
                'url' => $url
            ];
        }

        return $urls;
    }

    public function getExpirationTime(string $key): int
    {
        $key = str_replace(self::REDIS_KEY_PREFIX, '', $key);
        return $this->redis->ttl($key);
    }

    public function updateExpirationTime(string $key, int $newTTL): void
    {
        $key = str_replace(self::REDIS_KEY_PREFIX, '', $key);
        $this->redis->expire($key, $newTTL);
    }

    private function generateUniqueKey(): string
    {
        for ($i = 0; $i < self::MAX_ATTEMPTS; $i++) {
            $key = bin2hex(random_bytes(self::KEY_LENGTH));
            if (!$this->redis->exists(self::REDIS_KEY_PREFIX . $key)) {
                return $key;
            }
        }

        throw new UniqueKeyGenerationException(
            'Failed to generate a unique URL key after ' . self::MAX_ATTEMPTS . ' attempts'
        );
    }
}