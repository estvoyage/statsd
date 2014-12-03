<?php

namespace estvoyage\statsd\value;

use
	estvoyage\statsd
;

class timing extends statsd\value
{
	function __construct($value)
	{
		parent::__construct($value, type\timing::build());
	}
}
