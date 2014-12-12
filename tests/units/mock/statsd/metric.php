<?php

namespace estvoyage\statsd;

class metric
{
	function __construct($value = '')
	{
		$this->value = $value;
	}

	function __toString()
	{
		return $this->value;
	}
}
