<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\statsd
;

abstract class generic implements statsd\metric
{
	private
		$bucket,
		$value,
		$sampling
	;

	function __construct(bucket $bucket, value $value, sampling $sampling = null)
	{
		$this->bucket = $bucket;
		$this->value = $value;
		$this->sampling = $sampling;
	}

	protected function isCountingAndStatsdMetricTemplateIs(template $template)
	{
		$template->statsdCountingContainsBucketAndValueAndSampling($this->bucket, $this->value, $this->sampling);

		return $this;
	}

	protected function isGaugeAndStatsdMetricTemplateIs(template $template)
	{
		$template->statsdGaugeContainsBucketAndValue($this->bucket, $this->value);

		return $this;
	}

	protected function isTimingAndStatsdMetricTemplateIs(template $template)
	{
		$template->statsdTimingContainsBucketAndValue($this->bucket, $this->value);

		return $this;
	}
}
