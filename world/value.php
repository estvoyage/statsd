<?php

namespace estvoyage\statsd\world;

interface value
{
	function send(bucket $bucket, connection $connection, value\sampling $sampling = null, connection\socket\timeout $timeout = null);
}
