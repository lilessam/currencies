<?php
namespace Lilessam\Currencies;

use Illuminate\Support\ServiceProvider;

class CurrenciesServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/currencies.php' => config_path('currencies.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        require_once __DIR__.'/Currency.php';

        $this->mergeConfigFrom(
            __DIR__ . '/config/currencies.php', 'currencies'
        );
    }
}
