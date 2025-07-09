<?php

declare(strict_types=1);

use Lumen\TwMerge\Support\Validators;

it('can use IsAnyValidator', function (): void {
    expect(resolve(Validators\IsAnyValidator::class)())->toBeTrue();
    expect(resolve(Validators\IsAnyValidator::class)(''))->toBeTrue();
    expect(resolve(Validators\IsAnyValidator::class)('foo'))->toBeTrue();
});

it('can use IsAnyNonArbitrary', function (): void {
    expect(resolve(Validators\IsAnyNonArbitrary::class)('test'))->toBeTrue();
    expect(resolve(Validators\IsAnyNonArbitrary::class)('1234-hello-world'))->toBeTrue();
    expect(resolve(Validators\IsAnyNonArbitrary::class)('[hello'))->toBeTrue();
    expect(resolve(Validators\IsAnyNonArbitrary::class)('hello]'))->toBeTrue();
    expect(resolve(Validators\IsAnyNonArbitrary::class)('[)'))->toBeTrue();
    expect(resolve(Validators\IsAnyNonArbitrary::class)('(hello]'))->toBeTrue();

    expect(resolve(Validators\IsAnyNonArbitrary::class)('[test]'))->toBeFalse();
    expect(resolve(Validators\IsAnyNonArbitrary::class)('[label:test]'))->toBeFalse();
    expect(resolve(Validators\IsAnyNonArbitrary::class)('(test)'))->toBeFalse();
    expect(resolve(Validators\IsAnyNonArbitrary::class)('(label:test)'))->toBeFalse();
});

it('can use IsArbitraryImageValidator', function (): void {
    expect(resolve(Validators\IsArbitraryImageValidator::class)('[url:var(--my-url)]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryImageValidator::class)('[url(something)]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryImageValidator::class)('[url:bla]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryImageValidator::class)('[image:bla]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryImageValidator::class)('[linear-gradient(something)]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryImageValidator::class)('[repeating-conic-gradient(something)]'))->toBeTrue();

    expect(resolve(Validators\IsArbitraryImageValidator::class)('[var(--my-url)]'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryImageValidator::class)('[bla]'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryImageValidator::class)('url:2px'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryImageValidator::class)('url(2px)'))->toBeFalse();
});

it('can use IsArbitraryLengthValidator', function (): void {
    expect(resolve(Validators\IsArbitraryLengthValidator::class)('[3.7%]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryLengthValidator::class)('[481px]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryLengthValidator::class)('[19.1rem]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryLengthValidator::class)('[50vw]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryLengthValidator::class)('[56vh]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryLengthValidator::class)('[length:var(--arbitrary)]'))->toBeTrue();

    expect(resolve(Validators\IsArbitraryLengthValidator::class)('1'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryLengthValidator::class)('3px'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryLengthValidator::class)('1d5'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryLengthValidator::class)('[1]'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryLengthValidator::class)('[12px'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryLengthValidator::class)('12px]'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryLengthValidator::class)('one'))->toBeFalse();
});

it('can use IsArbitraryNumberValidator', function (): void {
    expect(resolve(Validators\IsArbitraryNumberValidator::class)('[number:black]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryNumberValidator::class)('[number:bla]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryNumberValidator::class)('[number:230]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryNumberValidator::class)('[450]'))->toBeTrue();

    expect(resolve(Validators\IsArbitraryNumberValidator::class)('[2px]'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryNumberValidator::class)('[bla]'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryNumberValidator::class)('[black]'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryNumberValidator::class)('black'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryNumberValidator::class)('450'))->toBeFalse();
});

it('can use IsArbitraryPositionValidator', function (): void {
    expect(resolve(Validators\IsArbitraryPositionValidator::class)('[position:2px]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryPositionValidator::class)('[position:bla]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryPositionValidator::class)('[percentage:bla]'))->toBeTrue();

    expect(resolve(Validators\IsArbitraryPositionValidator::class)('[2px]'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryPositionValidator::class)('[bla]'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryPositionValidator::class)('position:2px'))->toBeFalse();
});

it('can use IsArbitraryShadowValidator', function (): void {
    expect(resolve(Validators\IsArbitraryShadowValidator::class)('[0_35px_60px_-15px_rgba(0,0,0,0.3)]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryShadowValidator::class)('[inset_0_1px_0,inset_0_-1px_0]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryShadowValidator::class)('[0_0_#00f]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryShadowValidator::class)('[.5rem_0_rgba(5,5,5,5)]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryShadowValidator::class)('[-.5rem_0_#123456]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryShadowValidator::class)('[0.5rem_-0_#123456]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryShadowValidator::class)('[0.5rem_-0.005vh_#123456]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryShadowValidator::class)('[0.5rem_-0.005vh]'))->toBeTrue();

    expect(resolve(Validators\IsArbitraryShadowValidator::class)('[rgba(5,5,5,5)]'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryShadowValidator::class)('[#00f]'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryShadowValidator::class)('[something-else]'))->toBeFalse();
});

it('can use IsArbitrarySizeValidator', function (): void {
    expect(resolve(Validators\IsArbitrarySizeValidator::class)('[size:2px]'))->toBeTrue();
    expect(resolve(Validators\IsArbitrarySizeValidator::class)('[size:bla]'))->toBeTrue();
    expect(resolve(Validators\IsArbitrarySizeValidator::class)('[length:bla]'))->toBeTrue();

    expect(resolve(Validators\IsArbitrarySizeValidator::class)('[2px]'))->toBeFalse();
    expect(resolve(Validators\IsArbitrarySizeValidator::class)('[bla]'))->toBeFalse();
    expect(resolve(Validators\IsArbitrarySizeValidator::class)('size:2px'))->toBeFalse();
    expect(resolve(Validators\IsArbitrarySizeValidator::class)('[percentage:bla]'))->toBeFalse();
});

it('can use IsArbitraryValueValidator', function (): void {
    expect(resolve(Validators\IsArbitraryValueValidator::class)('[1]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryValueValidator::class)('[bla]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryValueValidator::class)('[not-an-arbitrary-value?]'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryValueValidator::class)('[auto,auto,minmax(0,1fr),calc(100vw-50%)]'))->toBeTrue();

    expect(resolve(Validators\IsArbitraryValueValidator::class)('[]'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryValueValidator::class)('[1'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryValueValidator::class)('1]'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryValueValidator::class)('1'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryValueValidator::class)('one'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryValueValidator::class)('o[n]e'))->toBeFalse();
});

it('can use IsArbitraryVariableValidator', function (): void {
    expect(resolve(Validators\IsArbitraryVariableValidator::class)('(1)'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryVariableValidator::class)('(bla)'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryVariableValidator::class)('(not-an-arbitrary-value?)'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryVariableValidator::class)('(--my-arbitrary-variable)'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryVariableValidator::class)('(label:--my-arbitrary-variable)'))->toBeTrue();

    expect(resolve(Validators\IsArbitraryVariableValidator::class)('()'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryVariableValidator::class)('(1'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryVariableValidator::class)('1)'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryVariableValidator::class)('1'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryVariableValidator::class)('one'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryVariableValidator::class)('o(n)e'))->toBeFalse();
});

it('can use IsArbitraryVariableFamilyNameValidator', function (): void {
    expect(resolve(Validators\IsArbitraryVariableFamilyNameValidator::class)('(family-name:test)'))->toBeTrue();

    expect(resolve(Validators\IsArbitraryVariableFamilyNameValidator::class)('(other:test)'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryVariableFamilyNameValidator::class)('(test)'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryVariableFamilyNameValidator::class)('family-name:test'))->toBeFalse();
});

it('can use IsArbitraryVariableImageValidator', function (): void {
    expect(resolve(Validators\IsArbitraryVariableImageValidator::class)('(image:test)'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryVariableImageValidator::class)('(url:test)'))->toBeTrue();

    expect(resolve(Validators\IsArbitraryVariableImageValidator::class)('(other:test)'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryVariableImageValidator::class)('(test)'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryVariableImageValidator::class)('image:test'))->toBeFalse();
});

it('can use IsArbitraryVariableLengthValidator', function (): void {
    expect(resolve(Validators\IsArbitraryVariableLengthValidator::class)('(length:test)'))->toBeTrue();

    expect(resolve(Validators\IsArbitraryVariableLengthValidator::class)('(other:test)'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryVariableLengthValidator::class)('(test)'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryVariableLengthValidator::class)('length:test'))->toBeFalse();
});

it('can use IsArbitraryVariablePositionValidator', function (): void {
    expect(resolve(Validators\IsArbitraryVariablePositionValidator::class)('(position:test)'))->toBeTrue();

    expect(resolve(Validators\IsArbitraryVariablePositionValidator::class)('(other:test)'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryVariablePositionValidator::class)('(test)'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryVariablePositionValidator::class)('position:test'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryVariablePositionValidator::class)('percentage:test'))->toBeFalse();
});

it('can use IsArbitraryVariableShadowValidator', function (): void {
    expect(resolve(Validators\IsArbitraryVariableShadowValidator::class)('(shadow:test)'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryVariableShadowValidator::class)('(test)'))->toBeTrue();

    expect(resolve(Validators\IsArbitraryVariableShadowValidator::class)('(other:test)'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryVariableShadowValidator::class)('shadow:test'))->toBeFalse();
});

it('can use IsArbitraryVariableSizeValidator', function (): void {
    expect(resolve(Validators\IsArbitraryVariableSizeValidator::class)('(size:test)'))->toBeTrue();
    expect(resolve(Validators\IsArbitraryVariableSizeValidator::class)('(length:test)'))->toBeTrue();

    expect(resolve(Validators\IsArbitraryVariableSizeValidator::class)('(other:test)'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryVariableSizeValidator::class)('(test)'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryVariableSizeValidator::class)('size:test'))->toBeFalse();
    expect(resolve(Validators\IsArbitraryVariableSizeValidator::class)('(percentage:test)'))->toBeFalse();
});

it('can use IsFractionValidator', function (): void {
    expect(resolve(Validators\IsFractionValidator::class)('1/2'))->toBeTrue();
    expect(resolve(Validators\IsFractionValidator::class)('123/209'))->toBeTrue();

    expect(resolve(Validators\IsFractionValidator::class)('1'))->toBeFalse();
    expect(resolve(Validators\IsFractionValidator::class)('1/2/3'))->toBeFalse();
    expect(resolve(Validators\IsFractionValidator::class)('[1/2]'))->toBeFalse();
});

it('can use IsIntegerValidator', function (): void {
    expect(resolve(Validators\IsIntegerValidator::class)('1'))->toBeTrue();
    expect(resolve(Validators\IsIntegerValidator::class)('123'))->toBeTrue();
    expect(resolve(Validators\IsIntegerValidator::class)('8312'))->toBeTrue();

    expect(resolve(Validators\IsIntegerValidator::class)('[8312]'))->toBeFalse();
    expect(resolve(Validators\IsIntegerValidator::class)('[2]'))->toBeFalse();
    expect(resolve(Validators\IsIntegerValidator::class)('[8312px]'))->toBeFalse();
    expect(resolve(Validators\IsIntegerValidator::class)('[8312%]'))->toBeFalse();
    expect(resolve(Validators\IsIntegerValidator::class)('[8312rem]'))->toBeFalse();
    expect(resolve(Validators\IsIntegerValidator::class)('8312.2'))->toBeFalse();
    expect(resolve(Validators\IsIntegerValidator::class)('1.2'))->toBeFalse();
    expect(resolve(Validators\IsIntegerValidator::class)('one'))->toBeFalse();
    expect(resolve(Validators\IsIntegerValidator::class)('1/2'))->toBeFalse();
    expect(resolve(Validators\IsIntegerValidator::class)('1%'))->toBeFalse();
    expect(resolve(Validators\IsIntegerValidator::class)('1px'))->toBeFalse();
});

it('can use IsNumberValidator', function (): void {
    expect(resolve(Validators\IsNumberValidator::class)('1'))->toBeTrue();
    expect(resolve(Validators\IsNumberValidator::class)('123'))->toBeTrue();
    expect(resolve(Validators\IsNumberValidator::class)('8312'))->toBeTrue();
    expect(resolve(Validators\IsNumberValidator::class)('8312.2'))->toBeTrue();
    expect(resolve(Validators\IsNumberValidator::class)('1.2'))->toBeTrue();

    expect(resolve(Validators\IsNumberValidator::class)('[8312]'))->toBeFalse();
    expect(resolve(Validators\IsNumberValidator::class)('[2]'))->toBeFalse();
    expect(resolve(Validators\IsNumberValidator::class)('[8312px]'))->toBeFalse();
    expect(resolve(Validators\IsNumberValidator::class)('[8312%]'))->toBeFalse();
    expect(resolve(Validators\IsNumberValidator::class)('[8312rem]'))->toBeFalse();
    expect(resolve(Validators\IsNumberValidator::class)('one'))->toBeFalse();
    expect(resolve(Validators\IsNumberValidator::class)('1/2'))->toBeFalse();
    expect(resolve(Validators\IsNumberValidator::class)('1%'))->toBeFalse();
    expect(resolve(Validators\IsNumberValidator::class)('1px'))->toBeFalse();
});

it('can use IsPercentValidator', function (): void {
    expect(resolve(Validators\IsPercentValidator::class)('1%'))->toBeTrue();
    expect(resolve(Validators\IsPercentValidator::class)('100.001%'))->toBeTrue();
    expect(resolve(Validators\IsPercentValidator::class)('.01%'))->toBeTrue();
    expect(resolve(Validators\IsPercentValidator::class)('0%'))->toBeTrue();

    expect(resolve(Validators\IsPercentValidator::class)('0'))->toBeFalse();
    expect(resolve(Validators\IsPercentValidator::class)('one%'))->toBeFalse();
});

it('can use IsTshirtSizeValidator', function (): void {
    expect(resolve(Validators\IsTshirtSizeValidator::class)('xs'))->toBeTrue();
    expect(resolve(Validators\IsTshirtSizeValidator::class)('sm'))->toBeTrue();
    expect(resolve(Validators\IsTshirtSizeValidator::class)('md'))->toBeTrue();
    expect(resolve(Validators\IsTshirtSizeValidator::class)('lg'))->toBeTrue();
    expect(resolve(Validators\IsTshirtSizeValidator::class)('xl'))->toBeTrue();
    expect(resolve(Validators\IsTshirtSizeValidator::class)('2xl'))->toBeTrue();
    expect(resolve(Validators\IsTshirtSizeValidator::class)('2.5xl'))->toBeTrue();
    expect(resolve(Validators\IsTshirtSizeValidator::class)('10xl'))->toBeTrue();
    expect(resolve(Validators\IsTshirtSizeValidator::class)('2xs'))->toBeTrue();
    expect(resolve(Validators\IsTshirtSizeValidator::class)('2lg'))->toBeTrue();

    expect(resolve(Validators\IsTshirtSizeValidator::class)(''))->toBeFalse();
    expect(resolve(Validators\IsTshirtSizeValidator::class)('hello'))->toBeFalse();
    expect(resolve(Validators\IsTshirtSizeValidator::class)('1'))->toBeFalse();
    expect(resolve(Validators\IsTshirtSizeValidator::class)('xl3'))->toBeFalse();
    expect(resolve(Validators\IsTshirtSizeValidator::class)('2xl3'))->toBeFalse();
    expect(resolve(Validators\IsTshirtSizeValidator::class)('-xl'))->toBeFalse();
    expect(resolve(Validators\IsTshirtSizeValidator::class)('[sm]'))->toBeFalse();
});
