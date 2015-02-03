<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\value\string,
	estvoyage\value\integer as integerValidator,
	estvoyage\value\string as stringValidator,
	estvoyage\value\float as floatValidator
;

final class value extends string
{
	function __construct($value, $type, $sampling = 1.)
	{
		if (! integerValidator::validate($value))
		{
			throw new \domainException('Value should be an integer');
		}

		if (! stringValidator::validate($type) || $type == '')
		{
			throw new \domainException('Type should be a not empty string');
		}

		if ($sampling !== 1.  && (! floatValidator::validate($sampling) || $sampling <= 0.))
		{
			throw new \domainException('Sampling should be a float greater than 0.');
		}

		parent::__construct($value . '|' . $type . ($sampling == 1. ? '' : '|@' . $sampling));
	}

	static function gauge($value)
	{
		return new self($value, 'g');
	}

	static function counting($value = 1, $sampling = 1.)
	{
		return new self($value, 'c', $sampling);
	}

	static function set($value)
	{
		return new self($value, 's');
	}

	static function timing($value)
	{
		return new self($value, 'ms');
	}
}
