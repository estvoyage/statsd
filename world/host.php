<?php

namespace estvoyage\statsd\world;

interface host
{
	function openSocket(socket $socket, port $port, callable $callback);
}
