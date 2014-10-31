<?php

namespace estvoyage\statsd\world;

interface port
{
	function openSocket(socket $socket, $host);
}
