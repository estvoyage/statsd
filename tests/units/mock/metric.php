<?php

namespace estvoyage\statsd\tests\units\mock;

class metric
{
	function __construct($value)
	{
		$this->value = $value;
	}

	function __toString()
	{
		return $this->value;
	}
}

@class_alias(__NAMESPACE__ . '\metric', 'estvoyage\statsd\metric');
