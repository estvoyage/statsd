<?php

namespace estvoyage\statsd;

final class packet
{
	private
		$metrics
	;

	function __construct(metric ...$metrics)
	{
		$this->metrics = $metrics;
	}
}
