<?php

namespace App\Domain\Contracts;

interface ValidatableValueObjectInterface
{
    public function getValue(): string;
    public function __toString(): string;
}
