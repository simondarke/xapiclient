<?php

namespace SimonDarke\XapiClient\Objects;

use SimonDarke\XapiClient\Exceptions\InvalidIriException;
use SimonDarke\XapiClient\Exceptions\ValidationException;

readonly class InverseFunctionalIdentifier implements \JsonSerializable
{
    private function __construct(
        private string   $type,
        private ?string  $value,
        private ?Account $account,
    ) {}

    public static function mbox(string $value): self
    {
        self::validateMbox($value);
        $normalised = str_starts_with($value, 'mailto:') ? $value : "mailto:{$value}";
        return new self('mbox', $normalised, null);
    }

    public static function mboxSha1Sum(string $value): self
    {
        self::validateMboxSha1Sum($value);
        return new self('mbox_sha1sum', $value, null);
    }

    public static function openId(string $value): self
    {
        if (empty($value)) {
            throw InvalidIriException::missing('openId');
        }

        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw InvalidIriException::invalidUrl('openId', $value);
        }
        return new self('openid', $value, null);
    }

    public static function account(Account $account): self
    {
        return new self('account', null, $account);
    }

    public function jsonSerialize(): array
    {
        return match ($this->type) {
            'mbox' => ['mbox' => $this->value],
            'mbox_sha1sum' => ['mbox_sha1sum' => $this->value],
            'openid' => ['openid' => $this->value],
            'account' => ['account' => $this->account->jsonSerialize()],
        };
    }


    private static function validateMbox($value): void
    {
        $value = str_starts_with($value, 'mailto:') ? substr($value, 7) : $value;

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::invalidField('mbox', 'invalid email address given');
        }
    }

    private static function validateMboxSha1Sum($value): void
    {
        if (!ctype_xdigit($value) || strlen($value) !== 40) {
            throw InvalidIriException::invalidField('mbox_sha1sum', 'must be a 40 character hex string');
        }
    }
}
