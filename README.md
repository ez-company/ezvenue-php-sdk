EZ Venue PHP SDK
============================

PHP SDK for [EZ Venue API](https://www.ezvenue.us/docs/api/).

## Installation
```term
$ composer require ezvenue/ezvenue-php-sdk
```

## Usage
```php
use \EZVenue\EZVenue;
use \EZVenue\Lookup;

const EZV_USERNAME = 'YOUR_EZV_USERNAME';
const EZV_ACCESS_TOKEN = 'YOUR_EZV_ACCESS_TOKEN';

$ezv = new EZVenue(EZV_USERNAME, EZV_ACCESS_TOKEN);

$data = [
    'ref' => 'XXXX',
    'amount' => 1000,
    'address' => '1234 Your Address, TX 12345' // can be a coordinates e.g. 123.233334,-93.334343
];

$lookup = $ezv->createLookup($data);
print_r($lookup);
```

## License
Released under the [MIT License](http://opensource.org/licenses/MIT).
See [LICENSE](LICENSE) file.
