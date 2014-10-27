<?php

namespace estvoyage\statsd\world;

interface metric
{
	function writeOn(connection $connection, callable $callback);
}
