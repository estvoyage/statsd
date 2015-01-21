<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\value\float
;

class sampling extends float\unsigned
{
	function __construct($value = 1.0)
	{
		$exception = null;

		try
		{
			parent::__construct($value);
		}
		catch (\domainException $exception) {}

		if ($exception || ! self::isGreaterThanZero($value))
		{
			throw new \domainException('Sampling should be a float greater than 0.');
		}
	}

	static function validate($value)
	{
		return parent::validate($value) && self::isGreaterThanZero($value);
	}

	private static function isGreaterThanZero($value)
	{
		return $value > 0.;
	}
}