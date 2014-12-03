<?php

namespace estvoyage\statsd\value;

use
	estvoyage\statsd
;

class set extends statsd\value
{
	function __construct($value)
	{
		parent::__construct($value, type\set::build());
	}
}
