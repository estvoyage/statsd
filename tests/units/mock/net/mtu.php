<?php

namespace estvoyage\net;

class mtu
{
	function __construct($value = 68)
	{
		$this->asInteger = $value;
	}

	static function build($value = 68)
	{
		return new self($value);
	}
}
