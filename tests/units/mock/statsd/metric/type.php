<?php

namespace estvoyage\statsd\metric;

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