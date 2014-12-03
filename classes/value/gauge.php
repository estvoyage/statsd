<?php

namespace estvoyage\statsd\value;

use
	estvoyage\statsd
;

class gauge extends statsd\value
{
	function __construct($value)
	{
		parent::__construct($value, type\gauge::build());
	}
}
