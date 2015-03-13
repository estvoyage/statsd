<?php

namespace estvoyage\statsd\metric\template;

use
	estvoyage\data,
	estvoyage\statsd\metric
;

final class etsy implements metric\template
{
	private
		$dataConsumer,
		$metric
	;

	function __construct(data\consumer $dataConsumer)
	{
		$this->dataConsumer = $dataConsumer;
	}

	function dataConsumerIs(data\consumer $dataConsumer)
	{
		return new self($dataConsumer);
	}

	function newStatsdMetric(metric $metric)
	{
		$metric->statsdMetricTemplateIs($this);

		return $this;
	}

	function statsdCountingContainsBucketAndValueAndSampling(metric\bucket $bucket, metric\value $value, metric\sampling $sampling = null)
	{
		return $this->newBucketAndValueAndTypeAndSampling($bucket, new data\data((string) $value), new data\data('c'), $sampling);
	}

	function statsdTimingContainsBucketAndValue(metric\bucket $bucket, metric\value $value)
	{
		return $this->newBucketAndValueAndTypeAndSampling($bucket, new data\data((string) $value), new data\data('ms'));
	}

	function statsdGaugeContainsBucketAndValue(metric\bucket $bucket, metric\value $value)
	{
		return $this->newBucketAndValueAndTypeAndSampling($bucket, new data\data((string) $value), new data\data('g'));
	}

	function statsdGaugeUpdateContainsBucketAndValue(metric\bucket $bucket, metric\value $value)
	{
		return $this->newBucketAndValueAndTypeAndSampling($bucket, new data\data(($value->asInteger < 0 ? '-' : '+') . $value), new data\data('g'));
	}

	function statsdSetContainsBucketAndValue(metric\bucket $bucket, metric\value $value)
	{
		return $this->newBucketAndValueAndTypeAndSampling($bucket, new data\data((string) $value), new data\data('s'));
	}

	private function newBucketAndValueAndTypeAndSampling(metric\bucket $bucket, data\data $value, data\data $type, metric\sampling $sampling = null)
	{
		if ($sampling)
		{
			$sampling = $sampling->asFloat == 1. ? '' : '|@' . $sampling;
		}

		$this->dataConsumer->newData(new data\data($bucket . ':' . $value . '|' . $type . $sampling . "\n"));

		return $this;
	}
}
