<?php

namespace estvoyage\statsd\probe;

use
	estvoyage\statsd\metric,
	estvoyage\statsd\world as statsd
;

final class timer
{
	private
		$client,
		$start
	;

	function __construct(statsd\client $client)
	{
		$this->client = $client;
		$this->start = self::now();
	}

	function bucketIs(metric\bucket $bucket)
	{
		$this->client->valueGoesInto(metric\value::timing(self::now() - $this->start), $bucket);

		return $this;
	}

	private static function now()
	{
		return microtime(true) * 10000;
	}
}
