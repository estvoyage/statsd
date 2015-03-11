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
	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\client')
		;
	}

	function testDataConsumerIs()
	{
		$this
			->given(
				$dataConsumer = new mockOfData\consumer,
				$this->newTestedInstance($dataConsumer)
			)
			->if(
				$otherDataConsumer = new mockOfData\consumer
			)
			->then
				->object($this->testedInstance->dataConsumerIs($otherDataConsumer))
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance($otherDataConsumer))
		;
	}

	function testNewStatsdMetric()
	{
		$this
			->given(
				$dataConsumer = new mockOfData\consumer,
				$this->newTestedInstance($dataConsumer)
			)
			->if(
				$metric = new mockOfStatsd\metric
			)
			->then
				->object($this->testedInstance->newStatsdMetric($metric))
					->isTestedInstance
				->mock($metric)
					->receive('statsdMetricFactoryIs')
						->withArguments(new metric\factory\etsy($dataConsumer))
							->once
		;
	}

	function testStatsdMetricProviderIs()
	{
		$this
			->given(
				$dataConsumer = new mockOfData\consumer,
				$this->newTestedInstance($dataConsumer)
			)
			->if(
				$statsdMetricProvider = new mockOfStatsd\metric\provider
			)
			->then
				->object($this->testedInstance->statsdMetricProviderIs($statsdMetricProvider))
					->isTestedInstance
				->mock($statsdMetricProvider)
					->receive('statsdMetricFactoryIs')
						->withArguments(new metric\factory\etsy($dataConsumer))
							->once
		;
	}
}
