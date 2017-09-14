<?php
namespace Lilessam\Currencies;

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
		$rates = file_get_contents(self::$baseUrl."?base=".$from."&symbols=".$to);
		$rates = (object) json_decode($rates);

		return $rates->rates->{$to} * $value;
	}

	/**
	 * Get rates of value.
	 * @param  decimal $value
	 * @param  string $from
	 * @param  string|array $to
	 * @return json
	 */
	public static function getRates($value, $from, $to = null)
	{
		$parameters = self::getParameters($from, $to);

		$rates = file_get_contents(self::$baseUrl."?".$parameters);
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
		$parameters  = "base=".$from;

		if(!$to) {
			
			if (is_array($to)) {
				$parameters .= "&symbols=".implode(',', $to);
			}

			$parameters .= "&symbols=".$to;
		}

		return $parameters;
	}
}