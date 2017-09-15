### Currencies Package For Laravel
This package provides two simple helpers to convert amounts from currency to another and to get today's rates for any currency.
##### Helpers in the package are using an external API which provides real time currency rates.

### Installation
via composer
```bash
composer require lilessam/currencies
```
Then add `Lilessam\Currencies\CurrenciesServiceProvider` to your providers array in `config/app.php`.

### Using the package
You can use the helper `convert_currency` which requires three parameters. First is the amount, the second is the amount current currency and the third is the currency you want to change the amount to.
```PHP
// This will convert 10 from USD to EUR with today's price
$amount = convert_currency(10, 'USD', 'EUR');
```
You can also use `get_rates` helper to get an object of today's currencies rates. Its first parameter is required which is the base currency. The second parameter is optional and its type is array which allows you to pass what currencies you wanna get rates for.
```PHP
// This will return an object of all USD currencies today's rates
$rates = get_rates('USD');
// This will return an object of Euro, Indian Rubles and Arab Emirates Dirham rates for USD.
$rates = get_rates('USD', ['EUR', 'INR', 'AUD']);
```

### Cache settings
The package now uses Laravel cache system to cache the data from the external API to reduce the API calls as much as possible. So you can use the following command to publish the package configuration file and change how many minutes you wanna keep the cached data.
```bash
php artisan vendor:publish --provider=Lilessam\Currencies\CurrenciesServiceProvider
```
The package caches the data for 60 minutes by default.


###### The package has been developed in almost 30 mins, So please post any issue if found or make a pull request. Thanks !