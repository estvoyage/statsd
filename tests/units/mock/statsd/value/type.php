<?php

namespace estvoyage\statsd\value;

class type
{
	function __construct($value)
	{
		$this->value = $value;
	}

	function __toString()
	{
		return (string) $this->value;
	}
}
