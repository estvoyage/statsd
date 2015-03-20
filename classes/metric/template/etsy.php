<?php

namespace estvoyage\statsd\metric\template;

use
	estvoyage\net,
	estvoyage\data,
	estvoyage\statsd\metric
;

final class etsy implements metric\template
{
	private
		$data
	;

	function __construct()
	{
		$this->data = new data\data;
	}

	function statsdMetricConsumerIs(metric\consumer $metricConsumer)
	{
		$metricConsumer->newDataFromStatsdMetricTemplate($this->data, $this);

		$this->data = new data\data;

		return $this;
	}

	function mtuOfStatsdMetricConsumerIs(metric\consumer $metricConsumer, net\mtu $mtu)
	{
		$metrics = new data\data;

		while (strlen($this->data))
		{
			$metric = new data\data(substr($this->data, 0, strpos($this->data, "\n") + 1));

			switch (true)
			{
				case strlen($metric) > $mtu->asInteger:
					throw new net\mtu\exception\overflow('Unable to split metric \'' . $metric . '\' according to MTU ' . $mtu);

				case strlen($metrics . $metric) > $mtu->asInteger:
					$metricConsumer->newDataFromStatsdMetricTemplate($metrics, $this);
					$metrics = new data\data;
			}

			$this->data = new data\data(substr($this->data, strlen($metric)) ?: '');

			$metrics = $metrics->newData($metric);
		}

		$metricConsumer->newDataFromStatsdMetricTemplate($metrics, $this);

		return $this;
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

	function statsdTimingContainsBucketAndValueAndSampling(metric\bucket $bucket, metric\value $value, metric\sampling $sampling = null)
	{
		return $this->newBucketAndValueAndTypeAndSampling($bucket, new data\data((string) $value), new data\data('ms'), $sampling);
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

		$this->data = $this->data->newData(new data\data($bucket . ':' . $value . '|' . $type . $sampling . "\n"));

		return $this;
	}
}
