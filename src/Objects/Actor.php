<?php

namespace SimonDarke\XapiClient\Objects;

use SimonDarke\XapiClient\Exceptions\ValidationException;

readonly class Actor implements \JsonSerializable
{
    private function __construct(
        private string                       $objectType,
        private ?string                      $name,
        private ?InverseFunctionalIdentifier $ifi,
        private array                        $members,
    ) {}

    public static function agent(
        InverseFunctionalIdentifier $inverseFunctionalIdentifier,
        ?string $name = null,
    ): self
    {
        return new self('Agent', $name, $inverseFunctionalIdentifier, []);
    }

    public static function group(
        ?InverseFunctionalIdentifier $inverseFunctionalIdentifier = null,
        ?string $name = null,
        array $members = [],
    ): self
    {
        if ($inverseFunctionalIdentifier === null && empty($members)) {
            throw ValidationException::invalidField('members', 'an anonymous group must have at least one member');
        }

        foreach ($members as $member) {
            if (!$member instanceof Actor) {
                throw ValidationException::invalidField('members', 'all members must be Actor instances');
            }
        }

        return new self('Group', $name, $inverseFunctionalIdentifier, $members);
    }


    public function jsonSerialize(): array
    {
        $data = ['objectType' => $this->objectType];

        if ($this->name !== null) {
            $data['name'] = $this->name;
        }

        if ($this->ifi !== null) {
            $data = array_merge($data, $this->ifi->jsonSerialize());
        }

        if (!empty($this->members)) {
            $data['member'] = array_map(fn(Actor $a) => $a->jsonSerialize(), $this->members);
        }

        return $data;
    }
}