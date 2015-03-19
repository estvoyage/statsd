<?php

namespace estvoyage\statsd\metric\consumer;

use
	estvoyage\net,
	estvoyage\data,
	estvoyage\statsd\metric
;

final class dataConsumer implements metric\consumer
{
	private
		$dataConsumer
	;

	function __construct(data\consumer $dataConsumer)
	{
		$this->dataConsumer = $dataConsumer;
	}

	function statsdMetricTemplateIs(metric\template $metricTemplate)
	{
		$metricTemplate->statsdMetricConsumerIs($this);

		return $this;
	}

	function newDataFromStatsdMetricTemplate(data\data $data, metric\template $metricTemplate)
	{
		$this->dataConsumer->newData($data);

		return $this;
	}
}
