<?php

namespace estvoyage\statsd\world\connection;

use
	estvoyage\statsd\world as statsd
;

interface mtu
{
	function reset();
	function resetIfTrue($boolean);
	function add($data);
	function addIfNotEmpty($data);
	function writeOn(statsd\socket $socket);
	function writeIfTrueOn($boolean, statsd\socket $socket);
}
