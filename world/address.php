<?php

namespace estvoyage\statsd\world;

use
	estvoyage\statsd\world as statsd
;

interface address
{
	function openSocket(statsd\socket $socket, callable $callback);
}
