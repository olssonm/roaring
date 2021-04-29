# Roaring.io API-wrapper

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Software License][ico-php]][link-packagist]
[![Scrutinizer Score][ico-scrutinizer]][link-scrutinizer]

This is a (very) simple wrapper for the [roaring.io](https://www.roaring.io/en/) API.

The wrapper is designed to be quick and easy to use – no fuzz. Just create a new object with your API-keys and then call the endpoint you want to call. Though you yourself has to do the data-manipulation to your needs and liking.

The package also includes a service provider for Laravel.

On a sidenote; this package uses the [httpful](https://github.com/nategood/httpful)-library for the HTTP-requests. While [Guzzle](https://github.com/guzzle/guzzle) and the like may generally be recommended, it is easy to introduce conflicts in some frameworks with different versions of those more common libraries.

## Requirements

PHP ^7.3 / ^8.0

If you want to use the Laravel Service Provider, Laravel 5.5 and above is supported.

## Installation

```php
$ composer require olssonm/roaring
```

### Laravel

Laravel should auto-discover the service provider. You may also manually add it to your providers-array in `config/app.php`:

```php
'providers' => [
    Olssonm\Roaring\Laravel\ServiceProvider::class
]
```

You may set an alias using the facade in `config/app.php`:

```php
'aliases' => [
    'Roaring' => Olssonm\Roaring\Laravel\Facades\Roaring::class
]
```

For the Roaring object to initialise properly using dependancy injection/the facade, you will need to set your key and secret in `/config/services.php`:

```
'roaring' => [
    'key' => env('ROARING_KEY', 'xxx'),
    'secret' => env('ROARING_SECRET', 'zzz')
]
```

## Usage

Using the wrapper is very simple – just initiate the object and call the endpoint you wish to use.

Used standalone/via main class:

```php
use \Olssonm\Roaring\Roaring;

$response = (new Roaring('key', 'secret'))
    ->get('/se/company/overview/1.1/5567164818')
    ->getResponse();
```

Via the Laravel facade/dependancy injection:

```php
use Roaring;

$response = Roaring::get('/se/company/overview/1.1/5567164818')
    ->getResponse();
```

Roaring.io uses the OAuth-protocol – currently a new OAuth token is created automatically upon initialisation unless an already existing token is passed to the `Roaring`-constructor.

Because the `getResponse()`-method always returns the latest response you can retrieve the token data by just creating a new object and returning the response:

```php
$token = (new Roaring('key', 'secret'))->getResponse('body');

var_dump($token);

// object(stdClass)#26 (4) {
//   ["access_token"]=>
//   string(36) "xxxx-xxxx-xxxx-xxxx-xxxx"
//   ["scope"]=>
//   string(28) "am_application_scope default"
//   ["token_type"]=>
//   string(6) "Bearer"
//   ["expires_in"]=>
//   int(2184)
// }

```

You can also use `getToken()` to retrieve it. This gives you the ability to reuse a token (mostly it is quite unnecessary though – just saves you a request if you know your token is still valid) by passing it as the third parameter:

```php
use Olssonm\Roaring\Roaring;

$response = (new Roaring('key', 'secret', $token))
    ->get('/se/company/overview/1.1/5567164818')
    ->getResponse();
```

The returned object is always of the type `stdClass` (internally httpful just unpacks the returned JSON to setup the object).

With `getResponse()` you will recieve the entire response, you may also for example use `getResponse('body')` to only retrieve the `body`, `getResponse('code')` to get the `code`-attribute and so on.

## Testing

First you will need sandbox-keys from roaring.io, once obtained copy `/tests/config.example.json` to `/tests/config.json`, set your keys and then run:

``` bash
$ composer test
```

or

``` bash
$ phpunit
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

© 2020 [Marcus Olsson](https://marcusolsson.me).

[ico-version]: https://img.shields.io/packagist/v/olssonm/roaring.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/olssonm/roaring/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/g/olssonm/roaring.svg?style=flat-square
[ico-php]: https://img.shields.io/packagist/php-v/olssonm/roaring.svg?style=flat-square
[link-packagist]: https://packagist.org/packages/olssonm/roaring
[link-travis]: https://travis-ci.org/olssonm/roaring
[link-scrutinizer]: https://scrutinizer-ci.com/g/olssonm/roaring
