<?php

namespace estvoyage\statsd;

use
	estvoyage\value\integer
;

class value extends integer
{
	private
		$type
	;

	function __construct($value, value\type $type)
	{
		parent::__construct($value);

		$this->type = $type;
	}

	function __toString()
	{
		return parent::__toString() . '|' . $this->type;
	}
}
