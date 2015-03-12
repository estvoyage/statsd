<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\statsd,
	estvoyage\statsd\metric
;

final class counting extends generic
{
	function __construct(bucket $bucket, value $value = null, sampling $sampling = null)
	{
		parent::__construct($bucket, $value ?: new value(1), $sampling);
	}

	function statsdMetricTemplateIs(template $template)
	{
		return $this->isCountingAndStatsdMetricTemplateIs($template);
	}
}
