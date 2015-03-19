<?php

namespace estvoyage\statsd\metric\consumer;

use
	estvoyage\net,
	estvoyage\data,
	estvoyage\statsd\metric
;

final class dataConsumerWithMtu implements metric\consumer
{
	private
		$dataConsumer,
		$mtu
	;

	function __construct(data\consumer $dataConsumer, net\mtu $mtu)
	{
		$this->dataConsumer = $dataConsumer;
		$this->mtu = $mtu;
	}

	function statsdMetricTemplateIs(metric\template $metricTemplate)
	{
		$metricTemplate->statsdMetricConsumerIs($this);

		return $this;
	}

	function newDataFromStatsdMetricTemplate(data\data $data, metric\template $metricTemplate)
	{
		switch (true)
		{
			case strlen($data) > $this->mtu->asInteger:
				$metricTemplate->mtuOfStatsdMetricConsumerIs($this, $this->mtu);
				return $this;

			default:
				$this->dataConsumer->newData($data);
		}

		return $this;
	}
}
