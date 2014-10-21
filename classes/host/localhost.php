<?php

namespace estvoyage\statsd\host;

use
	estvoyage\statsd\host
;

class localhost extends host
{
	function __construct()
	{
		parent::__construct('127.0.0.1');
	}
}
