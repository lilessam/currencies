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
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        require_once __DIR__.'/Currency.php';
    }
}
