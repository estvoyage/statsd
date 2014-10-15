<?php

namespace estvoyage\statsd\world;

interface bucket
{
	function send($value, connection $connection, $timeout = null);
}
