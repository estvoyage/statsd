<?php

namespace estvoyage\statsd;

use
	estvoyage\net,
	estvoyage\statsd\world as statsd
;

abstract class connection
{
	private
		$address,
		$socket,
		$mtu
	;

	function __construct(net\address $address, net\world\socket $socket, net\mtu $mtu)
	{
		$this->address = $address;
		$this->socket = $socket;
		$this->mtu = $mtu;
	}

	function send(statsd\packet $packet)
	{
		$packet->writeOn($this->socket, $this->address, $this->mtu);

		return $this;
	}
}
