<?php

namespace estvoyage\statsd\value;

use
	estvoyage\statsd
;

class counting extends statsd\value
{
	private
		$sampling
	;

	function __construct($value, statsd\value\sampling $sampling = null)
	{
		parent::__construct($value, type\counting::build());

		$this->sampling = $sampling ?: new statsd\value\sampling;
	}

	function __toString()
	{
		return parent::__toString() . ($this->sampling->asFloat == 1. ? '' : '|@' . $this->sampling);
	}
}
