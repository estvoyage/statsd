<?php

namespace estvoyage\statsd\connection;

use
	estvoyage\net,
	estvoyage\statsd
;

final class intranet extends statsd\connection
{
	function __construct(net\address $address, net\world\socket $socket)
	{
		parent::__construct($address, $socket, net\mtu::build(1432));
	}
}
