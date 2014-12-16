<?php

namespace estvoyage\statsd;

use
	estvoyage\net,
	estvoyage\statsd\world as statsd
;

abstract class connection implements statsd\connection
{
	private
		$socket,
		$mtu
	;

	function __construct(net\world\socket $socket, net\mtu $mtu)
	{
		$this->socket = $socket;
		$this->mtu = $mtu;
	}

	function send(statsd\packet $packet)
	{
		$packet->writeOn($this->socket, $this->mtu);

		return $this;
	}
}
