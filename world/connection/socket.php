<?php

namespace seshat\statsd\world\connection;

use
	seshat\statsd\world\packet
;

interface socket
{
	function sendPacketTo(packet $packet, $host, $port, $timeout = null);
	function sendTo($dara, $host, $port, $timeout = null);
}
