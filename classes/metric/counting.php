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
			type\counting::build(),
			$sampling === null ? new sampling(1.0) : $sampling
		);
	}

	static function from($bucketAsString, $valueAsInteger = 1, $samplingAsFloat = 1.)
	{
		return new self(
			bucket::ofName($bucketAsString),
			new value($valueAsInteger),
			new sampling($samplingAsFloat)
		);
	}
}
