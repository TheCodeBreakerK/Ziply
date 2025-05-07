<?php

namespace App\Domain\ValueObjects;

use http\Exception\InvalidArgumentException;

class Url
{
    private string $value;

    public function __construct(string $url)
    {
        $this->validate($url);
        $this->value = $url;
    }

    public function validate(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException("Invalid URL");
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }
}