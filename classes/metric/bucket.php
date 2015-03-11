<?php

namespace estvoyage\statsd\metric;

final class bucket extends \estvoyage\value\string
{
	const allowedCharacters = '-+a-z0-9_{}:%\]\[';

	function __construct($name)
	{
		$domainException = null;

		try
		{
			parent::__construct($name);
		}
		catch (\exception $domainException) {}

		if ($domainException || ! self::containsAllowedCharacters($name))
		{
			throw new \domainException('Bucket name should be a string which contains alphanumeric characters, -, +, _, {, }, [, ], %');
		}
	}

	static function validate($name)
	{
		return parent::validate($name) && self::containsAllowedCharacters($name);
	}

	private static function containsAllowedCharacters($string)
	{
		return preg_match('/^(?:[' . self::allowedCharacters . ']+\.)*[' . self::allowedCharacters . ']+$/i', $string);
	}
}
