<?php

use Lilessam\Currencies\Currency;

/**
 * Convert value from currency to another
 * @param  decimal $value
 * @param  string  $from
 * @param  string  $to
 * @return decimal
 */
function convert_currency($value, $from, $to)
{
    return Currency::convertCurrency($value, $from, $to);
}

/**
 * Get rates of value.
 * @param  decimal $value
 * @param  string $from
 * @param  string|array $to
 * @return json
 */
function get_rates($from, $to = null)
{
    return Currency::getRates($from, $to);
}
