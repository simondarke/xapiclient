<?php

use SimonDarke\XapiClient\Exceptions\ValidationException;
use SimonDarke\XapiClient\Exceptions\XapiException;

it('is an instance of XapiException', function () {
    $exception = ValidationException::invalidField('name', 'must not be empty');

    expect($exception)->toBeInstanceOf(XapiException::class);
});

it('generates a consistent message from invalidField', function () {
    $exception = ValidationException::invalidField('name', 'must not be empty');

    expect($exception->getMessage())
        ->toBe("Validation failed for 'name': must not be empty");
});

it('can be caught as a ValidationException', function () {
    expect(function () {
        throw ValidationException::invalidField('homePage', 'must be a valid URL');
    })->toThrow(ValidationException::class);
});