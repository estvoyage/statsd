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

	function statsdClientIs(statsd\client $client)
	{
		$client->newStatsdMetric($this);

		return $this;
	}

	function parentBucketIs(bucket $bucket)
	{
		$metric = clone $this;
		$metric->bucket = $this->bucket->parentBucketIs($bucket);

		return $metric;
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
		$template->statsdTimingContainsBucketAndValueAndSampling($this->bucket, $this->value, $this->sampling);

		return $this;
	}

	protected function isSetAndStatsdMetricTemplateIs(template $template)
	{
		$template->statsdSetContainsBucketAndValue($this->bucket, $this->value);

		return $this;
	}
}
