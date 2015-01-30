<?php

namespace estvoyage\statsd\metric;

class bucket
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
