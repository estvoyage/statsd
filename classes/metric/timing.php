<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\statsd
;

final class timing extends statsd\metric
{
	function __construct(bucket $bucket, value $value)
	{
		parent::__construct($bucket, $value, new type('ms'));
	}
}
