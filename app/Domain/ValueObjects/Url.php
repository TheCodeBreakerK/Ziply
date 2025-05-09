<?php

namespace App\Domain\ValueObjects;

use http\Exception\InvalidArgumentException;

class Url
{
    private const int MAX_URL_LENGTH = 2000;
    private string $value;

    public function __construct(string $url)
    {
        $this->validate($url);
        $this->value = $url;
    }

    public function validate(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid URL format');
        }

        if (!preg_match('/^https?:\/\//i', $url)) {
            throw new InvalidArgumentException('Only HTTP and HTTPS protocols are allowed');
        }

        if (strlen($url) > self::MAX_URL_LENGTH) {
            throw new InvalidArgumentException('URL exceeds maximum length of ' . self::MAX_URL_LENGTH . ' characters');
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }
}