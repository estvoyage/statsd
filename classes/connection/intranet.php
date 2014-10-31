<?php

namespace estvoyage\statsd\connection;

use
	estvoyage\statsd,
	estvoyage\statsd\world\address
;

class intranet extends statsd\connection
{
	function __construct(address $address)
	{
		parent::__construct($address, new statsd\connection\mtu\intranet);
	}
}
