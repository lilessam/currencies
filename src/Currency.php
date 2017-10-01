<?php
namespace Lilessam\Currencies;

use Illuminate\Support\Facades\Cache;

class Currency
{
    public static $baseUrl = "http://api.fixer.io/latest";
    public static $api     = config('currencies.api');

    /**
     * Convert value from currency to another
     * @param  decimal $value
     * @param  string  $from
     * @param  string  $to
     * @return decimal
     */
    public static function convertCurrency($value, $from, $to)
    {
        $cache_name = 'rate.' . $from . '.' . $to . '.' . $value;

        if (Cache::has($cache_name)) {

            return Cache::get($cache_name);

        } else {
            if (static::$api == 'fixer') {
                $result = static::fixerConvertCurrency($value, $from, $to);
            } elseif (static::$api == 'google') {
                $result = static::googleConvertCurrency($value, $from, $to);
            }

            Cache::put($cache_name, $result, config('currencies.cache_minutes'));

            return $result;
        }
    }

    /**
     * Convert currency amount using FixerIO.
     * @param  float  $value
     * @param  string $from
     * @param  string $to
     * @return float
     */
    public static function fixerConvertCurrency(float $value, string $from, string $to) : float
    {
        $rates  = file_get_contents(self::$baseUrl . "?base=" . $from . "&symbols=" . $to);
        $rates  = (object) json_decode($rates);
        $result = $rates->rates->{$to} * $value;

        return $result;
    }

    /**
     * Convert currency amount using Google.
     * @param  float $amount
     * @param  string $from_currency
     * @param  string $to_currency
     * @return float
     */
    public static function googleConvertCurrency(float $amount, string $from_currency, string $to_currency) : float
    {
        $amount        = urlencode($amount);
        $from_currency = urlencode($from_currency);
        $to_currency   = urlencode($to_currency);

        $url = "https://finance.google.com/finance/converter?a=$amount&from=$from_currency&to=$to_currency";

        $ch      = curl_init();
        $timeout = 0;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_USERAGENT,
            "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $rawdata = curl_exec($ch);
        curl_close($ch);
        $data = explode('bld>', $rawdata);
        $data = explode($to_currency, $data[1]);

        return round($data[0], 2);
    }

    /**
     * Get rates of value. | Fixer IO Only
     * @param  decimal $value
     * @param  string $from
     * @param  array|null $to
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
