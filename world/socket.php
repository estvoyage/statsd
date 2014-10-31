<?php

namespace estvoyage\statsd\world;

use
	estvoyage\statsd\world as statsd
;

interface socket
{
	function open($host, $port);
	function write($data);
	function close();
}
