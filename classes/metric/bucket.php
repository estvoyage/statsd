<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\value
;

class bucket extends value\string
{
	const allowedCharacters = '-+a-z0-9_{}:%\]\[';

	function __construct($value)
	{
		if (! self::validate($value))
		{
			throw new \domainException('Bucket should be a string which contains alphanumeric characters, -, +, _, {, }, [, ], %');
		}

		parent::__construct($value);
	}

	static function validate($value)
	{
		return parent::validate($value) && self::isValidBucket($value);
	}

	private static function isValidBucket($string)
	{
		return preg_match('/^(?:[' . self::allowedCharacters . ']+\.)*[' . self::allowedCharacters . ']+$/i', $string);
	}
}
