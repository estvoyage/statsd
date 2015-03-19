<?php

namespace estvoyage\statsd\tests\units\client;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\net,
	estvoyage\statsd\metric,
	mock\estvoyage\data as mockOfData,
	mock\estvoyage\statsd as mockOfStatsd
;

class etsy extends units\test
{
	function beforeTestMethod($method)
	{
		require_once 'mock/statsd/metric/template/etsy.php';
	}

	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\client')
		;
	}

	function testNewStatsdMetric()
	{
		$this
			->given(
				$metricConsumer = new mockOfStatsd\metric\consumer,
				$this->newTestedInstance($metricConsumer)
			)
			->if(
				$metric = new mockOfStatsd\metric
			)
			->then
				->object($this->testedInstance->newStatsdMetric($metric))
					->isTestedInstance
				->mock($metricConsumer)
					->receive('statsdMetricTemplateIs')
						->withArguments((new metric\template\etsy)->newStatsdMetric($metric))
							->once
		;
	}

	function testStatsdMetricProviderIs()
	{
		$this
			->given(
				$this->newTestedInstance(new mockOfStatsd\metric\consumer)
			)
			->if(
				$statsdMetricProvider = new mockOfStatsd\metric\provider
			)
			->then
				->object($this->testedInstance->statsdMetricProviderIs($statsdMetricProvider))
					->isTestedInstance
				->mock($statsdMetricProvider)
					->receive('statsdClientIs')
						->withArguments($this->testedInstance)
							->once
		;
	}
}
