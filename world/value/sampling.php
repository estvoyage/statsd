<?php

namespace estvoyage\statsd\world\value;

use
	estvoyage\statsd\world as statsd
;

interface sampling
{
	function send(statsd\bucket $bucket, $value, $type, statsd\connection $connection, $timeout = null);
}
