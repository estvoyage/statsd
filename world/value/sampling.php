<?php

namespace estvoyage\statsd\world\value;

use
	estvoyage\statsd\world as statsd
;

interface sampling
{
	function send($value, statsd\connection $connection, statsd\connection\socket\timeout $timeout = null);
}
