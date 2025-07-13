# Lumen-ui Tailwind Merge

[![GitHub License](https://img.shields.io/github/license/nuxtifyts/php-dto)](https://github.com/Fa-BRAIK/lumen-tw/blob/main/LICENSE.md)
[![PHP](https://img.shields.io/badge/php-%23777BB4.svg?&logo=php&logoColor=white&label=8.4)](https://www.php.net/releases/8.4/en.php)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/lumen-ui/lumen-tw.svg?style=flat-square)](https://packagist.org/packages/lumen-ui/lumen-tw)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/lumen/lumen-tw/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/lumen/lumen-tw/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/lumen-ui/lumen-tw.svg?style=flat-square)](https://packagist.org/packages/lumen-ui/lumen-tw)

A [tailwind-merge](https://github.com/dcastil/tailwind-merge) port for Laravel. Support up to version 4.1 of Tailwind CSS.

<p align="center">
    <picture>
        <source media="(prefers-color-scheme: dark)" srcset="https://github.com/Fa-BRAIK/lumen-tw/blob/main/art/dark%3Ahow-to-use.png">
        <img alt="LumenTw example" src="https://github.com/Fa-BRAIK/lumen-tw/blob/main/art/light%3Ahow-to-use.png" height="300px">
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
twMerge('px-2 py-1 bg-red hover:bg-dark-red', 'p-3 bg-[#B91C1C]')
```

```php
// Via service container
app('lumenTw')->merge('px-2 py-1 bg-red hover:bg-dark-red', 'p-3 bg-[#B91C1C]');
```

```php
// Via Facade
use Lumen\TwMerge\Facades\TwMerge;

TwMerge::merge('px-2 py-1 bg-red hover:bg-dark-red', 'p-3 bg-[#B91C1C]');
```

## Supported Tailwind CSS Versions

This package supports Tailwind CSS versions up to 4.1.
- ☑️ 4.1.0
- ☑️ 4.0.0
- ☑️ 3.x (Legacy)

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Fa-BRAIK](https://github.com/Fa-BRAIK)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
