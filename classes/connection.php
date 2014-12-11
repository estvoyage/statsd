<?php

namespace estvoyage\statsd;

use
	estvoyage\net
;

class connection
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

	function sendMetric(metric $metric)
	{
		$data = new net\socket\data((string) (new packet)->add($metric));

		if (strlen($data) > $this->mtu->asInteger)
		{
			throw new net\mtu\overflow('Metric length exceed MTU');
		}

		$this->socket->write($data, $this->address);

		return $this;
	}
}
