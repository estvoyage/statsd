<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\value
;

class bucket extends value\string
{
	function __construct($string)
	{
		$invalid = false;

		try
		{
			parent::__construct($string);
		}
		catch (\exception $exception)
		{
			$invalid = true;
		}

		if ($invalid || ! self::isValidBucket($string))
		{
			throw new \domainException('Bucket should be a string which contains alphanumeric characters or underscore');
		}
	}

	static function validate($value)
	{
		return parent::validate($value) && self::isValidBucket($value);
	}

	private static function isValidBucket($string)
	{
		return preg_match('/^(?:[a-z0-9_]+\.)*[a-z0-9_]+$/i', $string);
	}
}
