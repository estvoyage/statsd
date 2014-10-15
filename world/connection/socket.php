<?php

namespace estvoyage\statsd\world\connection;

interface socket
{
	function send($data, $host, $port, $timeout = null);
}
