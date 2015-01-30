<?php

namespace estvoyage\statsd\probe\memory;

use
	estvoyage\statsd\metric,
	estvoyage\statsd\world as statsd
;

final class usage
{
	private
		$client,
		$bucket,
		$start
	;

	function __construct(statsd\client $client, metric\bucket $bucket)
	{
		$this->client = $client;
		$this->bucket = $bucket;
	}

	function startOfMission()
	{
		if ($this->start)
		{
			throw new \logicException('Mission is already started');
		}

		$this->start = memory_get_usage(true);

		return $this;
	}

	function endOfMission()
	{
		if (! $this->start)
		{
			throw new \logicException('Mission is not started');
		}

		$this->client
			->valueGoesInto(
				metric\value::gauge(memory_get_usage(true) - $this->start),
				$this->bucket
			)
		;

		return $this;
	}
}
