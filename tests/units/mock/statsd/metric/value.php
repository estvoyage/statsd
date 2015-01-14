<?php

namespace estvoyage\statsd\metric;

class value
{
	function __construct($value = '')
	{
		$this->value = $value;
	}

	function __toString()
	{
		return (string) $this->value;
	}
}
