<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\value
;

final class bucket
{
	use value\world\string {
		__construct as private;
		validate as isString;
	}

	const allowedCharacters = '-+a-z0-9_{}:%\]\[';

	function parentIs(self $parent)
	{
		return self::ofName($parent . '.' . $this);
	}

	static function validate($name)
	{
		return self::isString($name) && self::containsAllowedCharacters($name);
	}

	static function ofName($name)
	{
		static $buckets = [];

		if (! self::validate($name))
		{
			throw new \domainException('Bucket name should be a string which contains alphanumeric characters, -, +, _, {, }, [, ], %');
		}

		if (! isset($buckets[$name]))
		{
			$buckets[$name] = new self($name);
		}

		return $buckets[$name];
	}

	private static function containsAllowedCharacters($string)
	{
		return preg_match('/^(?:[' . self::allowedCharacters . ']+\.)*[' . self::allowedCharacters . ']+$/i', $string);
	}
}
