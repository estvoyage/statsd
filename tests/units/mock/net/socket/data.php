<?php

namespace estvoyage\net\socket;

class data
{
	function __construct($value = '')
	{
		$this->asString = $value;
	}

	function __toString()
	{
		return $this->asString;
	}
}
