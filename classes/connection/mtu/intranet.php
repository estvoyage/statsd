<?php

namespace estvoyage\statsd\connection\mtu;

use
	estvoyage\statsd\connection
;

class intranet extends connection\mtu
{
	function __construct()
	{
		parent::__construct(1432);
	}
}
