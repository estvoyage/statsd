<?php

namespace estvoyage\statsd\world\connection;

use
	estvoyage\statsd\world\packet
;

interface socket
{
	function sendPacketTo(packet $packet, $host, $port, $timeout = null);
	function sendTo($dara, $host, $port, $timeout = null);
}
