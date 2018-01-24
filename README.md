# Halda PHP SDK

[![Build Status](https://travis-ci.org/szeidler/halda-php-sdk.svg?branch=master)](https://travis-ci.org/szeidler/halda-php-sdk)

Halda PHP SDK utilizes [guzzle-services](https://github.com/guzzle/guzzle-services) for an easy integration with
[Halda's](https://www.halda.se/) Taximeter API.

## Requirements

* PHP 5.6.0 or greater (PHP 7 recommended)
* Composer
* Guzzle

## Installation

Add Halda PHP SDK as a composer dependency.

`composer require szeidler/halda-php-sdk:^1.0`

## Usage

Returns the asset representation based on the resource url of the asset.

```php
use Halda\HaldaClient;

$client = new HaldaClient([
    'baseUrl'  => '"https://www.halda.se/HaldaAppService/AppService.svc',
    'code' => 'Azd9kDr2V',
]);

$order = $client->lookupOrder(['Orderid' => 12345]);
print $order->offsetGet('ExternalOrderNo');
```

## Testing

This SDK includes PHPUnit as a composer `require-dev`. Copy the `phpunit.xml.dist` to `phpunit.xml` and fill in with
your API testing credentials.

`./vendor/bin/phpunit -c phpunit.xml`

## Credits

Stephan Zeidler for [Ramsalt Lab AS](https://ramsalt.com)

## License

The MIT License (MIT)
