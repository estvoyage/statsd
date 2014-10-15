<?php

namespace estvoyage\statsd\world;

interface bucket
{
	function send($value, $type, $sampling, connection $connection, $timeout = null);
}
