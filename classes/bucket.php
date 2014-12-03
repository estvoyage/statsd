<?php

namespace estvoyage\statsd;

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
			throw new \domainException('Bucket should be a not empty string');
		}
	}

	static function validate($value)
	{
		return parent::validate($value) && self::isValidBucket($value);
	}

	private static function isValidBucket($string)
	{
		return $string !== '' && preg_match('/[\s@|]/', $string) === 0;
	}
}
