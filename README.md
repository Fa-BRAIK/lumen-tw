# Lumen-ui Tailwind Merge

[![GitHub License](https://img.shields.io/github/license/nuxtifyts/php-dto)](https://github.com/Fa-BRAIK/lumen-tw/blob/main/LICENSE.md)
[![PHP](https://img.shields.io/badge/php-%23777BB4.svg?&logo=php&logoColor=white&label=8.4)](https://www.php.net/releases/8.4/en.php)
[![PhpStan Level](https://img.shields.io/badge/PHPStan-level%2010-brightgreen.svg)](https://phpstan.org/user-guide/rule-levels)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/lumen-ui/lumen-tw.svg?style=flat-square)](https://packagist.org/packages/lumen-ui/lumen-tw)
[![run-tests](https://github.com/Fa-BRAIK/lumen-tw/actions/workflows/run-tests.yml/badge.svg)](https://github.com/Fa-BRAIK/lumen-tw/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/lumen-ui/lumen-tw.svg?style=flat-square)](https://packagist.org/packages/lumen-ui/lumen-tw)

A [tailwind-merge](https://github.com/dcastil/tailwind-merge) port for Laravel. Support up to version 4.1 of Tailwind CSS.

<p align="center">
    <picture>
        <source media="(prefers-color-scheme: dark)" srcset="https://github.com/Fa-BRAIK/lumen-tw/blob/main/art/dark%3Ahow-to-use.png">
        <img alt="LumenTw example" src="https://github.com/Fa-BRAIK/lumen-tw/blob/main/art/light%3Ahow-to-use.png" height="250px">
    </picture>
</p>

## Installation

You can install the package via composer:

```bash
composer require lumen-ui/lumen-tw
```

## Usage

```php
// Via global function
tw_merge('px-2 py-1 bg-red hover:bg-dark-red', 'p-3 bg-[#B91C1C]')
```

```php
// Via service container
app('twMerge')->merge('px-2 py-1 bg-red hover:bg-dark-red', 'p-3 bg-[#B91C1C]');
```

```php
// Via Facade
use Lumen\TwMerge\Facades\TwMerge;

TwMerge::merge('px-2 py-1 bg-red hover:bg-dark-red', 'p-3 bg-[#B91C1C]');
```

## Supported Laravel Versions
- ☑️ 11.x
- ☑️ 12.x

## Supported Tailwind CSS Versions

This package supports Tailwind CSS versions up to 4.1.
- ☑️ 4.1.0
- ☑️ 4.0.0
- ☑️ 3.x (Legacy)

## Reference

- [Configuration](https://github.com/Fa-BRAIK/lumen-tw/tree/main/docs/configuration.md)
- [CssClassBuilder](https://github.com/Fa-BRAIK/lumen-tw/tree/main/docs/css-class-builder.md)
- [Api Reference](https://github.com/Fa-BRAIK/lumen-tw/tree/main/docs/api-reference.md)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Acknowledgements

This package is a port of the [tailwind-merge](https://github.com/dcastil/tailwind-merge) for Laravel.

## Credits

- [Fa-BRAIK](https://github.com/Fa-BRAIK)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
