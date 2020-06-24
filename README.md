# Cryptocurrency
## Note: this package is not ready for production.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/samirdjelal/cryptocurrency.svg?style=flat-square)](https://packagist.org/packages/samirdjelal/cryptocurrency)
[![Build Status](https://img.shields.io/travis/samirdjelal/cryptocurrency/master.svg?style=flat-square)](https://travis-ci.org/samirdjelal/cryptocurrency)
[![Quality Score](https://img.shields.io/scrutinizer/g/samirdjelal/cryptocurrency.svg?style=flat-square)](https://scrutinizer-ci.com/g/samirdjelal/cryptocurrency)
[![Total Downloads](https://img.shields.io/packagist/dt/samirdjelal/cryptocurrency.svg?style=flat-square)](https://packagist.org/packages/samirdjelal/cryptocurrency)

Cryptocurrency toolkit let you integrate cryptocurrencies payment system into your existing laravel application.

Currently it support bitcoin.

## Installation

You can install the package via composer:

```bash
composer require samirdjelal/cryptocurrency
```

Add the following fields to your `.env` file.
```
BLOCKCHAIN_API_KEY=
BLOCKCHAIN_XPUB=
CALLBACK_SECRET_KEY=
```


## Usage

``` php

// Get the current price of 1 BTC in USD.
Cryptocurrency::bitcoin()->price();

// Generate a unique bitcoin address for a specific order id. 
Cryptocurrency::bitcoin()->orderId('abcd11')->address();

// The gap between the last used address and the last generated address.
Cryptocurrency::bitcoin()->gap();

// Get information about a callback that already exists for a specific orderId.
Cryptocurrency::bitcoin()->orderId('abcd11')->callback();

// Check if a payment is made.
Cryptocurrency::bitcoin()->orderId('abcd11')->check();

```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email samir.djelal.webdesign@gmail.com instead of using the issue tracker.

## Credits

- [Samir Djelal](https://github.com/samirdjelal)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
