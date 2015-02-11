<?php

namespace estvoyage\statsd;

use
	estvoyage\net
;

final class connection implements packet\collector
{
	private
		$socket,
		$mtu
	;

	function __construct(net\socket\client\socket $socket, net\mtu $mtu = null)
	{
		$this->socket = $socket;
		$this->mtu = $mtu ?: new net\mtu(512);
	}

	function newPacket(packet $packet)
	{
		$packet->socketHasMtu($this->socket, $this->mtu);

		return $this;
	}
}
