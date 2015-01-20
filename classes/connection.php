<?php

namespace estvoyage\statsd;

use
	estvoyage\net,
	estvoyage\statsd\world as statsd
;

final class connection implements statsd\connection
{
	private
		$socket,
		$mtu
	;

	function __construct(net\world\socket $socket = null, net\mtu $mtu = null)
	{
		$this->socket = $socket ?: new net\socket\udp(new net\host('127.0.0.1'), new net\port(8125));
		$this->mtu = $mtu ?: new net\mtu(512);
	}

	function newPacket(statsd\packet $packet)
	{
		$packet->socketHasMtu($this->socket, $this->mtu);

		return $this;
	}
}
