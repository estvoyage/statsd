<?php

namespace estvoyage\statsd;

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
