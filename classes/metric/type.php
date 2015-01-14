<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\value\world
;

abstract class type
{
	use world\string {
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
