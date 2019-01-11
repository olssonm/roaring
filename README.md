# Roaring.io API-wrapper

This is a (very) simple wrapper for the roaring.io API.

The wrapper is designed to have really no constraints in place, just create a new object with your API-keys and then call the endpoint you want to call. You are fully free to do the error-checking and data manipulation yourself.

The package also includes a service provider for Laravel.

One a sidenote this package uses the [httpful](https://github.com/nategood/httpful)-library for the HTTP-requests. While [Guzzle](https://github.com/guzzle/guzzle) and the like may generally be preferred, it is easy to introduce conflicts in some frameworks with different versions of those libraries.

### Requirements

PHP 7.1 and up (still running PHP 5.6? It is now end of life!)

If you want to use the Laravel Service Provider, Laravel 5.5 and above is supported.

### Installation

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

For the Roaring object to initialise properly using dependancy injection, you will need to set your key and secret in `/config/services.php`:

```
'roaring' => [
    'key' => env('ROARING_KEY', xxx),
    'password' => env('ROARING_SECRET', zzz)
]
```

### Usage

Using the wrapper is very simple – just initiate the object and call the endpoint you wish to use:

```php
use \Olssonm\Roaring\Roaring;

$response = (new Roaring('key', 'secret'))
    ->get('/se/company/overview/1.1/5567164818')
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

With `getResponse()` you will recieve the entire response, you may also for example use `getResponse('body')` to only retrieve the `body`, `getResponse('code')` to get the `code`-parameter and so on.

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

© 2019 [Marcus Olsson](https://marcusolsson.me).
