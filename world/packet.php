<?php

namespace seshat\statsd\world;

use
	seshat\statsd\world\connection\socket
;

interface packet
{
	function writeOnSocket(socket $socket, $host, $port, $timeout);
}
