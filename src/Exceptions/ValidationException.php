<?php

namespace SimonDarke\XapiClient\Exceptions;

class ValidationException extends XapiException
{
    public static function invalidField(string $field, string $reason): self
    {
        return new self("Validation failed for '{$field}': {$reason}");
    }
}
