<?php

use SimonDarke\XapiClient\Exceptions\ValidationException;
use SimonDarke\XapiClient\Objects\Account;
use SimonDarke\XapiClient\Objects\Actor;
use SimonDarke\XapiClient\Objects\InverseFunctionalIdentifier;

it('Agent validates correctly for Agent with name', function () {
    $agent = Actor::agent(InverseFunctionalIdentifier::mbox('mailto:test@test.com'), 'test name');
    expect($agent)->toBeInstanceOf(Actor::class)->and($agent->jsonSerialize())->toBe([
        'objectType' => 'Agent',
        'name' => 'test name',
        'mbox' => 'mailto:test@test.com',
    ]);
})->group('actor', 'agent');

it('Agent validates correctly for Agent without name', function () {
    $agent = Actor::agent(InverseFunctionalIdentifier::mbox('mailto:test@test.com'));
    expect($agent)->toBeInstanceOf(Actor::class)->and($agent->jsonSerialize())->toBe([
        'objectType' => 'Agent',
        'mbox' => 'mailto:test@test.com',
    ]);
})->group('actor', 'agent');

it('Group validates successfully with ifi, name and no members', function () {
    $group = Actor::group(InverseFunctionalIdentifier::mbox('mailto:test@test.com'), 'test name', []);
    expect($group)->toBeInstanceOf(Actor::class)->and($group->jsonSerialize())->toBe([
        'objectType' => 'Group',
        'name' => 'test name',
        'mbox' => 'mailto:test@test.com',
    ]);
})->group('actor', 'group');

it('Group validates successfully with ifi, no name and no members', function () {
    $group = Actor::group(InverseFunctionalIdentifier::mbox('mailto:test@test.com'));
    expect($group)->toBeInstanceOf(Actor::class)->and($group->jsonSerialize())->toBe([
        'objectType' => 'Group',
        'mbox' => 'mailto:test@test.com',
    ]);
})->group('actor', 'group');

it('Group validates successfully with ifi, name and members', function () {
    $group = Actor::group(
        InverseFunctionalIdentifier::mbox('mailto:test@test.com'),
        'test name',
        [
            Actor::agent(InverseFunctionalIdentifier::mbox('mailto:test@test.com'))
        ]);
    expect($group)->toBeInstanceOf(Actor::class)->and($group->jsonSerialize())->toBe([
        'objectType' => 'Group',
        'name' => 'test name',
        'mbox' => 'mailto:test@test.com',
        'member' => [
            [
                'objectType' => 'Agent',
                'mbox' => 'mailto:test@test.com',
            ]
        ]
    ]);
})->group('actor', 'group');

dataset('valid agents', [
    'mbox agent' => [Actor::agent(InverseFunctionalIdentifier::mbox('mailto:test@test.com'))],
    'mboxSha1Sum agent' => [Actor::agent(InverseFunctionalIdentifier::mboxSha1Sum(sha1('test')))],
    'openId agent' => [Actor::agent(InverseFunctionalIdentifier::openId('http://test.com'))],
    'account agent' => [Actor::agent(InverseFunctionalIdentifier::account(new Account('test', 'http://test.com')))],
]);

it('constructs a named group with a member of each IFI type', function (Actor $member) {
    $group = Actor::group(
        InverseFunctionalIdentifier::mbox('mailto:group@test.com'),
        members: [$member],
    );
    expect($group)->toBeInstanceOf(Actor::class);
})->with('valid agents')->group('actor', 'group');

it('Throws correct exception when the anonymous members group has invalid member', function () {
    Actor::group(InverseFunctionalIdentifier::mbox('mailto:test@test.com'), members: ['not valid']);
})->throws(ValidationException::class, 'Validation failed for \'members\': all members must be Actor instances')->group('actor', 'group');

it('Throws correct exception when the anonymous members group has no members', function () {
    Actor::group(name: 'Group A');
})->throws(ValidationException::class, 'Validation failed for \'members\': an anonymous group must have at least one member')->group('actor', 'group');
