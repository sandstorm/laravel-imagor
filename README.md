# Laravel integration for ImgProxy

[![Latest Version on Packagist](https://img.shields.io/packagist/v/imsus/laravel-imgproxy.svg?style=flat-square)](https://packagist.org/packages/imsus/laravel-imgproxy)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/imsus/laravel-imgproxy/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/imsus/laravel-imgproxy/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/imsus/laravel-imgproxy/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/imsus/laravel-imgproxy/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/imsus/laravel-imgproxy.svg?style=flat-square)](https://packagist.org/packages/imsus/laravel-imgproxy)

This package provides a Laravel integration for ImgProxy, allowing you to easily generate and manipulate image URLs. It supports insecure, signed, and encrypted URL generation, and includes a helper function for quick usage.

## Installation

You can install the package via composer:

```bash
composer require imsus/laravel-imgproxy
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-imgproxy-config"
```

This is the contents of the published config file:

```php
return [
    'endpoint' => env('IMGPROXY_ENDPOINT', 'http://localhost:8080'),
    'key' => env('IMGPROXY_KEY'),
    'salt' => env('IMGPROXY_SALT'),
];
```

## Configuration

You can configure the package by updating the values in your `.env` file:

```dotenv
IMGPROXY_ENDPOINT=http://localhost:8080
IMGPROXY_KEY=your_key_here
IMGPROXY_SALT=your_salt_here
```

> [!NOTE]
> The `key' and `salt` are required only if you want to generate signed URLs. If you don't want to generate signed URLs, you can leave them empty.

> [!CAUTION]
> The `key` and `salt` should be in hex-encoded format.

## Usage

### URL Generation

```php
use Imsus\ImgProxy\Facades\ImgProxy;

// Generate URL using Facade
$url = ImgProxy::url('https://placehold.co/600x400/jpeg')
    ->setWidth(300)
    ->setHeight(200)
    ->build();

// Generate URL using helper function
$url = imgproxy('https://placehold.co/600x400/jpeg')
    ->setWidth(300)
    ->setHeight(200)
    ->build();
```

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

- [Imam Susanto](https://github.com/imsus)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
