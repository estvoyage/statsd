<?php

namespace estvoyage\net;

class mtu
{
	static function build($value = 68)
	{
		return new self($value);
	}

	private function __construct($value = 68)
	{
		$this->asInteger = $value;
	}

}
