<?php

namespace estvoyage\statsd\port;

use
	estvoyage\statsd\port
;

class statsd extends port
{
	function __construct()
	{
		parent::__construct(8125);
	}
}
