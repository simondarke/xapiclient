<?php

use SimonDarke\XapiClient\Exceptions\InvalidIriException;
use SimonDarke\XapiClient\Exceptions\XapiException;

it('is an instance of ValidationException', function () {
    $exception = InvalidIriException::invalidField('homePage', 'must be a valid URL');

    expect($exception)->toBeInstanceOf(XapiException::class);
});

it('generates a consistent message from missing field', function () {
    $exception = InvalidIriException::missing('homePage');

    expect($exception->getMessage())
        ->toBe("Field 'homePage' is required");
});

it('generates a consistent message for invalidUrl', function () {
    $exception = InvalidIriException::invalidUrl('homePage', 'not an IRI');

    expect($exception->getMessage())
        ->toBe("Field 'homePage' must be a valid absolute IRI, 'not an IRI' given");
});
