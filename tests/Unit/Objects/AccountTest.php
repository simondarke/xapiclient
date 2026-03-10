<?php

use SimonDarke\XapiClient\Exceptions\InvalidIriException;
use SimonDarke\XapiClient\Exceptions\ValidationException;
use SimonDarke\XapiClient\Objects\Account;

it('constructs successfully with valid data', function () {
    $account = new Account('valid name', 'http://www.validiri.com');
    expect($account->jsonSerialize()['name'])->toBe('valid name')->and($account->jsonSerialize()['homePage'])->toBe('http://www.validiri.com');
});

it('throws correct exception when name is empty', function () {
    new Account('', 'http://www.validiri.com');
})->throws(ValidationException::class, 'Validation failed for \'name\': must not be empty');

it('throws correct exception when homePage is empty', function () {
    new Account('valid name', '');
})->throws(InvalidIriException::class, 'Field \'homePage\' is required');

it('throws correct exception when homePage is invalid format', function () {
    new Account('valid name', 'not a valid url');
})->throws(InvalidIriException::class, 'Field \'homePage\' must be a valid absolute IRI, \'not a valid url\' given');
