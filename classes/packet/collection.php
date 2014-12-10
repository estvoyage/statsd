<?php

namespace estvoyage\statsd\packet;

use
	estvoyage\statsd,
	estvoyage\statsd\world\packet
;

final class collection implements \iteratorAggregate, packet\collection
{
	private
		$packets = []
	;

	function __construct(statsd\packet... $packets)
	{
		$this->packets = $packets;
	}

	function add(statsd\packet $packet, statsd\packet... $packets)
	{
		array_unshift($packets, $packet);

		return new self(... array_merge($this->packets, $packets));
	}

	function getIterator()
	{
	}
}
