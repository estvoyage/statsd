<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\statsd,
	estvoyage\statsd\metric
;

final class packet implements statsd\metric, statsd\client
{
	private
		$metrics
	;

	function __construct()
	{
		$this->metrics = [];
	}

	function parentBucketIs(metric\bucket $bucket)
	{
		foreach ($this->metrics as & $metric)
		{
			$metric = $metric->parentBucketIs($bucket);
		}

		return $this;
	}

	function statsdClientIs(statsd\client $client)
	{
		$client->newStatsdMetric($this);

		return $this;
	}

	function statsdMetricProviderIs(metric\provider $provider)
	{
		$provider->statsdClientIs($this);

		return $this;
	}

	function newStatsdMetric(metric $metric)
	{
		$this->metrics[] = $metric;

		return $this;
	}

	function statsdMetricTemplateIs(metric\template $template)
	{
		foreach ($this->metrics as $metric)
		{
			$template->newStatsdMetric($metric);
		}

		$this->metrics = [];

		return $this;
	}
}
