<?php

namespace estvoyage\statsd\value;

use
	estvoyage\value
;

abstract class type
{
	use value\immutable;

	protected static
		$instance
	;

	function __toString()
	{
		return $this->asString;
	}

	protected static function buildWith($value)
	{
		if (! static::$instance)
		{
			static::$instance = new static($value);
		}

		return static::$instance;
	}

	private function __construct($value)
	{
		$this->values['asString'] = $value;
	}
}
