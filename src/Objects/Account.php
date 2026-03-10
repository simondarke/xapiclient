<?php

namespace SimonDarke\XapiClient\Objects;

use SimonDarke\XapiClient\Exceptions\InvalidIriException;
use SimonDarke\XapiClient\Exceptions\ValidationException;

readonly class Account implements \JsonSerializable
{
    public function __construct(
        private string $name,
        private string $homePage,
    ) {
        if (empty($name)) {
            throw ValidationException::invalidField('name', 'must not be empty');
        }

        if (empty($homePage)) {
            throw InvalidIriException::missing('homePage');
        }

        if (!filter_var($homePage, FILTER_VALIDATE_URL)) {
            throw InvalidIriException::invalidUrl('homePage', $homePage);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHomePage(): string
    {
        return $this->homePage;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'homePage' => $this->homePage,
        ];
    }
}