<?php

namespace estvoyage\statsd\metric\value;

use
	estvoyage\value\world as value
;

abstract class type
{
	use value\string {
		__construct as private;
	}

	private static
		$instances
	;

	protected static function buildType($value)
	{
		if (! isset(self::$instances[$value]))
		{
			self::$instances[$value] = new static($value);
		}

		return self::$instances[$value];
	}
}
