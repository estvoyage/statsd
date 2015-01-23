<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\value
;

final class bucket extends value\string
{
	const allowedCharacters = '-+a-z0-9_{}:%\]\[';

	function __construct($value)
	{
		$domainException = null;

		try
		{
			parent::__construct($value);
		}
		catch (\exception $domainException) {}

		if ($domainException || ! self::isValidBucket($value))
		{
			throw new \domainException('Bucket should be a string which contains alphanumeric characters, -, +, _, {, }, [, ], %');
		}
	}

	function parentIs(self $parent)
	{
		return new self($parent . '.' . $this);
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
