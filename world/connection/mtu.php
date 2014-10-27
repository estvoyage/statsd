<?php

namespace estvoyage\statsd\world\connection;

use
	estvoyage\statsd\world as statsd
;

interface mtu
{
	function reset(callable $callback);
	function add($data, callable $callback);
	function addIfNotEmpty($data, callable $callback);
	function writeOn(statsd\socket $socket, callable $callback);
}
