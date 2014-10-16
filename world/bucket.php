<?php

namespace estvoyage\statsd\world;

interface bucket
{
	function send($value, connection $connection, value\sampling $sampling, connection\socket\timeout $timeout = null);
}
