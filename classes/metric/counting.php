<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\statsd
;

final class counting extends statsd\metric
{
	function __construct(bucket $bucket, value $value = null, sampling $sampling = null)
	{
		parent::__construct(
			$bucket,
			$value ?: new value(1),
			new type('c'),
			$sampling === null ? new sampling(1.0) : $sampling
		);
	}
}
