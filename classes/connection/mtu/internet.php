<?php

namespace estvoyage\statsd\connection\mtu;

use
	estvoyage\statsd\connection
;

class internet extends connection\mtu
{
	function __construct()
	{
		parent::__construct(512);
	}
}
