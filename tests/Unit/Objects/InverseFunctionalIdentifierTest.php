<?php

use SimonDarke\XapiClient\Exceptions\ValidationException;
use SimonDarke\XapiClient\Objects\InverseFunctionalIdentifier;

it('normalises plain email to mailto: prefix on serialization', function () {
    $mbox = InverseFunctionalIdentifier::mbox('test@test.com');
    expect($mbox->jsonSerialize())->toBeArray()->toMatchArray(['mbox' => 'mailto:test@test.com']);
})->group('mbox');

it('preserves existing mailto: prefix on serialization', function () {
    $mbox = InverseFunctionalIdentifier::mbox('mailto:test@test.com');
    expect($mbox->jsonSerialize())->toBeArray()->toMatchArray(['mbox' => 'mailto:test@test.com']);
})->group('mbox');

it('fails gracefully with an invalid email with mailto: prefix', function () {
    InverseFunctionalIdentifier::mbox('mailto:test');
})->throws(ValidationException::class, 'Validation failed for \'mbox\': invalid email address given')
    ->group('mbox');

it('fails gracefully with an invalid email without mailto: prefix', function () {
    InverseFunctionalIdentifier::mbox('test');
})->throws(ValidationException::class, 'Validation failed for \'mbox\': invalid email address given')
    ->group('mbox');

it('validates successfully', function () {
    $mboxSha1Sum = InverseFunctionalIdentifier::mboxSha1Sum(sha1('thisisateststring'));
    expect($mboxSha1Sum->jsonSerialize())->toBeArray()->toMatchArray(['mbox_sha1sum' => sha1('thisisateststring')]);
})->group('mboxSha1Sum');

it('throws exception when provided value is non hex string', function () {
    InverseFunctionalIdentifier::mboxSha1Sum('thisisateststring');
})->throws(ValidationException::class, 'Validation failed for \'mbox_sha1sum\': must be a 40 character hex string')
    ->group('mboxSha1Sum');

it('throws exception when provided value is less than 40 character hex string', function () {
    InverseFunctionalIdentifier::mboxSha1Sum('a94a8fe5ccb19ba61c4c0873d391e987982fbbd');
})->throws(ValidationException::class, 'Validation failed for \'mbox_sha1sum\': must be a 40 character hex string')
    ->group('mboxSha1Sum');

it('throws exception when provided value is more than 40 character hex string', function () {
    InverseFunctionalIdentifier::mboxSha1Sum('a94a8fe5ccb19ba61c4c0873d391e987982fbbd31');
})->throws(ValidationException::class, 'Validation failed for \'mbox_sha1sum\': must be a 40 character hex string')
    ->group('mboxSha1Sum');