<?php
namespace Lilessam\Currencies;

use Illuminate\Support\Facades\Cache;

class Currency
{
    public static $baseUrl = "http://api.fixer.io/latest";

    /**
     * Convert value from currency to another
     * @param  decimal $value
     * @param  string  $from
     * @param  string  $to
     * @return decimal
     */
    public static function convertCurrency($value, $from, $to)
    {
        $cache_name = 'rate' . $from . '.' . $to . '.' . $value;

        if (Cache::has($cache_name)) {

            return Cache::get($cache_name);

        } else {
            $rates  = file_get_contents(self::$baseUrl . "?base=" . $from . "&symbols=" . $to);
            $rates  = (object) json_decode($rates);
            $result = $rates->rates->{$to} * $value;

            Cache::put($cache_name, $result, config('currencies.cache_minutes'));

            return $result;
        }
    }

    /**
     * Get rates of value.
     * @param  decimal $value
     * @param  string $from
     * @param  array $to
     * @return json
     */
    public static function getRates($from, $to = null)
    {
        $cache_name = 'rates.' . $from;
        $cache_name .= ($to != null ? '.' . implode('.', $to) : '');

        if (Cache::has($cache_name)) {
            // Get rates from cache
            $rates = Cache::get($cache_name);
        } else {
            // Get rates from API
            $parameters = self::getParameters($from, $to);

            $rates = file_get_contents(self::$baseUrl . "?" . $parameters);

            Cache::put($cache_name, $rates, config('currencies.cache_minutes'));
        }

        $rates = (object) json_decode($rates);

        return $rates->rates;
    }

    /**
     * Prepare api query parameters.
     * @param  string $from
     * @param  string $to
     * @return string
     */
    public static function getParameters($from, $to)
    {
        $parameters = "base=" . $from;

        if (is_array($to)) {
            $parameters .= "&symbols=" . implode(',', $to);
        }

        return $parameters;
    }
}
