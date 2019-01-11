# Roaring.io API-wrapper

This is a (very) simple wrapper for the roaring.io API.

The wrapper is designed to have really no contraints in place, just create a new object with your API-keys and then call the endpoint you want to call. The package also includes a service provider for Laravel (Laravel 5.5 and up).

Uses the [httpful](https://github.com/nategood/httpful)-library for the HTTP-requests.

### Installation

```php
$ composer require olssonm/roaring
```

### Laravel

Laravel should auto-discover the service provider. You may also manually add it to your providers-array in `config/app.php`:

```php
'providers' => [
    Olssonm\VeryBasicAuth\VeryBasicAuthServiceProvider::class
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

Roaring.io uses the OAuth-protocol – currently a new OAuth token is created automatically upon initialisation unless a token is passed to the `Roaring`-constructor.

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

You can also use `getToken()` to retrieve it. This gives you the ability to reuse a token (mostly it is quite unnecessary though) by passing it as the third parameter:

```php
use \Olssonm\Roaring\Roaring;

$response = (new Roaring('key', 'secret', $token))
    ->get('/se/company/overview/1.1/5567164818')
    ->getResponse();
```

The returned object is always of the type stdClass (internally httpful just unpacks the returned JSON to setup the object).

With `getResponse()` you will recieve the entire response, you may also for example use `getResponse('body')` to only retrieve the `body`, `getResponse('code')` to get the `code`-parameter and so on.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

© 2019 [Marcus Olsson](https://marcusolsson.me).
