<?php

namespace estvoyage\statsd;

use
	estvoyage\value\world as value,
	estvoyage\net
;

class client
{
	use value\immutable;

	private
		$socket,
		$address,
		$mtu
	;

	function __construct(net\socket $socket, net\mtu $mtu = null, net\host $host = null, net\port $port = null)
	{
		$this->socket = $socket;
		$this->address = new net\address($host ?: new net\host, $port ?: new net\port(8125));
		$this->mtu = $mtu;

		$this->init(['host' => $this->address->host, 'port' => $this->address->port ]);
	}
}
