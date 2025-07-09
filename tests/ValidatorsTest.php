<?php

declare(strict_types=1);

use Lumen\TwMerge\Support\Validators;

it('can use IsAnyValidator', function (): void {
    $isAny = resolve(Validators\IsAnyValidator::class);

    expect($isAny())->toBeTrue()
        ->and($isAny(''))->toBeTrue()
        ->and($isAny('foo'))->toBeTrue();
});

it('can use IsAnyNonArbitrary', function (): void {
    $isAnyNonArbitrary = resolve(Validators\IsAnyNonArbitrary::class);

    expect($isAnyNonArbitrary('test'))->toBeTrue()
        ->and($isAnyNonArbitrary('1234-hello-world'))->toBeTrue()
        ->and($isAnyNonArbitrary('[hello'))->toBeTrue()
        ->and($isAnyNonArbitrary('hello]'))->toBeTrue()
        ->and($isAnyNonArbitrary('[)'))->toBeTrue()
        ->and($isAnyNonArbitrary('(hello]'))->toBeTrue()
        ->and($isAnyNonArbitrary('[test]'))->toBeFalse()
        ->and($isAnyNonArbitrary('[label:test]'))->toBeFalse()
        ->and($isAnyNonArbitrary('(test)'))->toBeFalse()
        ->and($isAnyNonArbitrary('(label:test)'))->toBeFalse();
});

it('can use IsArbitraryImageValidator', function (): void {
    $isArbitraryImage = resolve(Validators\IsArbitraryImageValidator::class);

    expect($isArbitraryImage('[url:var(--my-url)]'))->toBeTrue()
        ->and($isArbitraryImage('[url(something)]'))->toBeTrue()
        ->and($isArbitraryImage('[url:bla]'))->toBeTrue()
        ->and($isArbitraryImage('[image:bla]'))->toBeTrue()
        ->and($isArbitraryImage('[linear-gradient(something)]'))->toBeTrue()
        ->and($isArbitraryImage('[repeating-conic-gradient(something)]'))->toBeTrue()
        ->and($isArbitraryImage('[var(--my-url)]'))->toBeFalse()
        ->and($isArbitraryImage('[bla]'))->toBeFalse()
        ->and($isArbitraryImage('url:2px'))->toBeFalse()
        ->and($isArbitraryImage('url(2px)'))->toBeFalse();
});

it('can use IsArbitraryLengthValidator', function (): void {
    $isArbitraryLength = resolve(Validators\IsArbitraryLengthValidator::class);

    expect($isArbitraryLength('[3.7%]'))->toBeTrue()
        ->and($isArbitraryLength('[481px]'))->toBeTrue()
        ->and($isArbitraryLength('[19.1rem]'))->toBeTrue()
        ->and($isArbitraryLength('[50vw]'))->toBeTrue()
        ->and($isArbitraryLength('[56vh]'))->toBeTrue()
        ->and($isArbitraryLength('[length:var(--arbitrary)]'))->toBeTrue()
        ->and($isArbitraryLength('1'))->toBeFalse()
        ->and($isArbitraryLength('3px'))->toBeFalse()
        ->and($isArbitraryLength('1d5'))->toBeFalse()
        ->and($isArbitraryLength('[1]'))->toBeFalse()
        ->and($isArbitraryLength('[12px'))->toBeFalse()
        ->and($isArbitraryLength('12px]'))->toBeFalse()
        ->and($isArbitraryLength('one'))->toBeFalse();
});

it('can use IsArbitraryNumberValidator', function (): void {
    $isArbitraryNumber = resolve(Validators\IsArbitraryNumberValidator::class);

    expect($isArbitraryNumber('[number:black]'))->toBeTrue()
        ->and($isArbitraryNumber('[number:bla]'))->toBeTrue()
        ->and($isArbitraryNumber('[number:230]'))->toBeTrue()
        ->and($isArbitraryNumber('[450]'))->toBeTrue()
        ->and($isArbitraryNumber('[2px]'))->toBeFalse()
        ->and($isArbitraryNumber('[bla]'))->toBeFalse()
        ->and($isArbitraryNumber('[black]'))->toBeFalse()
        ->and($isArbitraryNumber('black'))->toBeFalse()
        ->and($isArbitraryNumber('450'))->toBeFalse();
});

it('can use IsArbitraryPositionValidator', function (): void {
    $isArbitraryPosition = resolve(Validators\IsArbitraryPositionValidator::class);

    expect($isArbitraryPosition('[position:2px]'))->toBeTrue()
        ->and($isArbitraryPosition('[position:bla]'))->toBeTrue()
        ->and($isArbitraryPosition('[percentage:bla]'))->toBeTrue()
        ->and($isArbitraryPosition('[2px]'))->toBeFalse()
        ->and($isArbitraryPosition('[bla]'))->toBeFalse()
        ->and($isArbitraryPosition('position:2px'))->toBeFalse();
});

it('can use IsArbitraryShadowValidator', function (): void {
    $isArbitraryShadow = resolve(Validators\IsArbitraryShadowValidator::class);

    expect($isArbitraryShadow('[0_35px_60px_-15px_rgba(0,0,0,0.3)]'))->toBeTrue()
        ->and($isArbitraryShadow('[inset_0_1px_0,inset_0_-1px_0]'))->toBeTrue()
        ->and($isArbitraryShadow('[0_0_#00f]'))->toBeTrue()
        ->and($isArbitraryShadow('[.5rem_0_rgba(5,5,5,5)]'))->toBeTrue()
        ->and($isArbitraryShadow('[-.5rem_0_#123456]'))->toBeTrue()
        ->and($isArbitraryShadow('[0.5rem_-0_#123456]'))->toBeTrue()
        ->and($isArbitraryShadow('[0.5rem_-0.005vh_#123456]'))->toBeTrue()
        ->and($isArbitraryShadow('[0.5rem_-0.005vh]'))->toBeTrue()
        ->and($isArbitraryShadow('[rgba(5,5,5,5)]'))->toBeFalse()
        ->and($isArbitraryShadow('[#00f]'))->toBeFalse()
        ->and($isArbitraryShadow('[something-else]'))->toBeFalse();
});

it('can use IsArbitrarySizeValidator', function (): void {
    $isArbitrarySize = resolve(Validators\IsArbitrarySizeValidator::class);

    expect($isArbitrarySize('[size:2px]'))->toBeTrue()
        ->and($isArbitrarySize('[size:bla]'))->toBeTrue()
        ->and($isArbitrarySize('[length:bla]'))->toBeTrue()
        ->and($isArbitrarySize('[2px]'))->toBeFalse()
        ->and($isArbitrarySize('[bla]'))->toBeFalse()
        ->and($isArbitrarySize('size:2px'))->toBeFalse()
        ->and($isArbitrarySize('[percentage:bla]'))->toBeFalse();
});

it('can use IsArbitraryValueValidator', function (): void {
    $isArbitraryValue = resolve(Validators\IsArbitraryValueValidator::class);

    expect($isArbitraryValue('[1]'))->toBeTrue()
        ->and($isArbitraryValue('[bla]'))->toBeTrue()
        ->and($isArbitraryValue('[not-an-arbitrary-value?]'))->toBeTrue()
        ->and($isArbitraryValue('[auto,auto,minmax(0,1fr),calc(100vw-50%)]'))->toBeTrue()
        ->and($isArbitraryValue('[]'))->toBeFalse()
        ->and($isArbitraryValue('[1'))->toBeFalse()
        ->and($isArbitraryValue('1]'))->toBeFalse()
        ->and($isArbitraryValue('1'))->toBeFalse()
        ->and($isArbitraryValue('one'))->toBeFalse()
        ->and($isArbitraryValue('o[n]e'))->toBeFalse();
});

it('can use IsArbitraryVariableValidator', function (): void {
    $isArbitraryVariable = resolve(Validators\IsArbitraryVariableValidator::class);

    expect($isArbitraryVariable('(1)'))->toBeTrue()
        ->and($isArbitraryVariable('(bla)'))->toBeTrue()
        ->and($isArbitraryVariable('(not-an-arbitrary-value?)'))->toBeTrue()
        ->and($isArbitraryVariable('(--my-arbitrary-variable)'))->toBeTrue()
        ->and($isArbitraryVariable('(label:--my-arbitrary-variable)'))->toBeTrue()
        ->and($isArbitraryVariable('()'))->toBeFalse()
        ->and($isArbitraryVariable('(1'))->toBeFalse()
        ->and($isArbitraryVariable('1)'))->toBeFalse()
        ->and($isArbitraryVariable('1'))->toBeFalse()
        ->and($isArbitraryVariable('one'))->toBeFalse()
        ->and($isArbitraryVariable('o(n)e'))->toBeFalse();
});

it('can use IsArbitraryVariableFamilyNameValidator', function (): void {
    $isArbitraryVariableFamilyName = resolve(Validators\IsArbitraryVariableFamilyNameValidator::class);

    expect($isArbitraryVariableFamilyName('(family-name:test)'))->toBeTrue()
        ->and($isArbitraryVariableFamilyName('(other:test)'))->toBeFalse()
        ->and($isArbitraryVariableFamilyName('(test)'))->toBeFalse()
        ->and($isArbitraryVariableFamilyName('family-name:test'))->toBeFalse();
});

it('can use IsArbitraryVariableImageValidator', function (): void {
    $isArbitraryVariableImage = resolve(Validators\IsArbitraryVariableImageValidator::class);

    expect($isArbitraryVariableImage('(image:test)'))->toBeTrue()
        ->and($isArbitraryVariableImage('(url:test)'))->toBeTrue()
        ->and($isArbitraryVariableImage('(other:test)'))->toBeFalse()
        ->and($isArbitraryVariableImage('(test)'))->toBeFalse()
        ->and($isArbitraryVariableImage('image:test'))->toBeFalse();
});

it('can use IsArbitraryVariableLengthValidator', function (): void {
    $isArbitraryVariableLength = resolve(Validators\IsArbitraryVariableLengthValidator::class);

    expect($isArbitraryVariableLength('(length:test)'))->toBeTrue()
        ->and($isArbitraryVariableLength('(other:test)'))->toBeFalse()
        ->and($isArbitraryVariableLength('(test)'))->toBeFalse()
        ->and($isArbitraryVariableLength('length:test'))->toBeFalse();
});

it('can use IsArbitraryVariablePositionValidator', function (): void {
    $isArbitraryVariablePosition = resolve(Validators\IsArbitraryVariablePositionValidator::class);

    expect($isArbitraryVariablePosition('(position:test)'))->toBeTrue()
        ->and($isArbitraryVariablePosition('(other:test)'))->toBeFalse()
        ->and($isArbitraryVariablePosition('(test)'))->toBeFalse()
        ->and($isArbitraryVariablePosition('position:test'))->toBeFalse()
        ->and($isArbitraryVariablePosition('percentage:test'))->toBeFalse();
});

it('can use IsArbitraryVariableShadowValidator', function (): void {
    $isArbitraryVariableShadow = resolve(Validators\IsArbitraryVariableShadowValidator::class);

    expect($isArbitraryVariableShadow('(shadow:test)'))->toBeTrue()
        ->and($isArbitraryVariableShadow('(test)'))->toBeTrue()
        ->and($isArbitraryVariableShadow('(other:test)'))->toBeFalse()
        ->and($isArbitraryVariableShadow('shadow:test'))->toBeFalse();
});

it('can use IsArbitraryVariableSizeValidator', function (): void {
    $isArbitraryVariableSize = resolve(Validators\IsArbitraryVariableSizeValidator::class);

    expect($isArbitraryVariableSize('(size:test)'))->toBeTrue()
        ->and($isArbitraryVariableSize('(length:test)'))->toBeTrue()
        ->and($isArbitraryVariableSize('(other:test)'))->toBeFalse()
        ->and($isArbitraryVariableSize('(test)'))->toBeFalse()
        ->and($isArbitraryVariableSize('size:test'))->toBeFalse()
        ->and($isArbitraryVariableSize('(percentage:test)'))->toBeFalse();
});

it('can use IsFractionValidator', function (): void {
    $isFraction = resolve(Validators\IsFractionValidator::class);

    expect($isFraction('1/2'))->toBeTrue()
        ->and($isFraction('123/209'))->toBeTrue()
        ->and($isFraction('1'))->toBeFalse()
        ->and($isFraction('1/2/3'))->toBeFalse()
        ->and($isFraction('[1/2]'))->toBeFalse();
});

it('can use IsIntegerValidator', function (): void {
    $isInteger = resolve(Validators\IsIntegerValidator::class);

    expect($isInteger('1'))->toBeTrue()
        ->and($isInteger('123'))->toBeTrue()
        ->and($isInteger('8312'))->toBeTrue()
        ->and($isInteger('[8312]'))->toBeFalse()
        ->and($isInteger('[2]'))->toBeFalse()
        ->and($isInteger('[8312px]'))->toBeFalse()
        ->and($isInteger('[8312%]'))->toBeFalse()
        ->and($isInteger('[8312rem]'))->toBeFalse()
        ->and($isInteger('8312.2'))->toBeFalse()
        ->and($isInteger('1.2'))->toBeFalse()
        ->and($isInteger('one'))->toBeFalse()
        ->and($isInteger('1/2'))->toBeFalse()
        ->and($isInteger('1%'))->toBeFalse()
        ->and($isInteger('1px'))->toBeFalse();
});

it('can use IsNumberValidator', function (): void {
    $isNumber = resolve(Validators\IsNumberValidator::class);

    expect($isNumber('1'))->toBeTrue()
        ->and($isNumber('123'))->toBeTrue()
        ->and($isNumber('8312'))->toBeTrue()
        ->and($isNumber('8312.2'))->toBeTrue()
        ->and($isNumber('1.2'))->toBeTrue()
        ->and($isNumber('[8312]'))->toBeFalse()
        ->and($isNumber('[2]'))->toBeFalse()
        ->and($isNumber('[8312px]'))->toBeFalse()
        ->and($isNumber('[8312%]'))->toBeFalse()
        ->and($isNumber('[8312rem]'))->toBeFalse()
        ->and($isNumber('one'))->toBeFalse()
        ->and($isNumber('1/2'))->toBeFalse()
        ->and($isNumber('1%'))->toBeFalse()
        ->and($isNumber('1px'))->toBeFalse();
});

it('can use IsPercentValidator', function (): void {
    $isPercent = resolve(Validators\IsPercentValidator::class);

    expect($isPercent('1%'))->toBeTrue()
        ->and($isPercent('100.001%'))->toBeTrue()
        ->and($isPercent('.01%'))->toBeTrue()
        ->and($isPercent('0%'))->toBeTrue()
        ->and($isPercent('0'))->toBeFalse()
        ->and($isPercent('one%'))->toBeFalse();
});

it('can use IsTshirtSizeValidator', function (): void {
    $isTshirtSize = resolve(Validators\IsTshirtSizeValidator::class);

    expect($isTshirtSize('xs'))->toBeTrue()
        ->and($isTshirtSize('sm'))->toBeTrue()
        ->and($isTshirtSize('md'))->toBeTrue()
        ->and($isTshirtSize('lg'))->toBeTrue()
        ->and($isTshirtSize('xl'))->toBeTrue()
        ->and($isTshirtSize('2xl'))->toBeTrue()
        ->and($isTshirtSize('2.5xl'))->toBeTrue()
        ->and($isTshirtSize('10xl'))->toBeTrue()
        ->and($isTshirtSize('2xs'))->toBeTrue()
        ->and($isTshirtSize('2lg'))->toBeTrue()
        ->and($isTshirtSize(''))->toBeFalse()
        ->and($isTshirtSize('hello'))->toBeFalse()
        ->and($isTshirtSize('1'))->toBeFalse()
        ->and($isTshirtSize('xl3'))->toBeFalse()
        ->and($isTshirtSize('2xl3'))->toBeFalse()
        ->and($isTshirtSize('-xl'))->toBeFalse()
        ->and($isTshirtSize('[sm]'))->toBeFalse();
});
