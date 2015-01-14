<?php

namespace estvoyage\statsd\metric\value;

class sampling
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
