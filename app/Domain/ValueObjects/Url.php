<?php

namespace App\Domain\ValueObjects;

use App\Domain\Contracts\ValidatableValueObjectInterface;
use App\Domain\Exceptions\InvalidUrlException;

class Url implements ValidatableValueObjectInterface
{
    private const int MAX_URL_LENGTH = 2000;
    private string $value;

    public function __construct(string $url)
    {
        $this->validate($url);
        $this->value = $url;
    }

    private function validate(string $url): void
    {
        $trimmed = trim($url);

        if (empty($trimmed)) {
            throw new InvalidUrlException("URL cannot be empty.");
        }

        if (!filter_var($trimmed, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException("URL format is invalid.");
        }

        $scheme = parse_url($trimmed, PHP_URL_SCHEME);
        if (!in_array(strtolower($scheme), ['http', 'https'])) {
            throw new InvalidUrlException("Only HTTP and HTTPS protocols are allowed.");
        }

        if (strlen($trimmed) > self::MAX_URL_LENGTH) {
            throw new InvalidUrlException("URL exceeds maximum length of " . self::MAX_URL_LENGTH . " characters.");
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}