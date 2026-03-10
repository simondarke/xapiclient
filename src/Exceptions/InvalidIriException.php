<?php

namespace SimonDarke\XapiClient\Exceptions;

class InvalidIriException extends ValidationException
{
    public static function missing(string $field): self
    {
        return new self("Field '{$field}' is required");
    }

    public static function invalidUrl(string $field, string $value): self
    {
        return new self("Field '{$field}' must be a valid absolute IRI, '{$value}' given");
    }
}
