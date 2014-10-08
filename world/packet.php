<?php

namespace estvoyage\statsd\world;

use
	estvoyage\statsd\world\connection\socket
;

interface packet
{
	function writeOnSocket(socket $socket, $host, $port, $timeout);
}
