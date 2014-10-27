<?php

namespace estvoyage\statsd\world;

interface packet
{
	function writeOn(connection $connection, callable $callback);
}
