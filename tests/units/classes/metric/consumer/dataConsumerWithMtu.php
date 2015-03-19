<?php

namespace estvoyage\statsd\tests\units\metric\consumer;

require __DIR__ . '/../../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\net,
	estvoyage\data,
	mock\estvoyage\data as mockOfData,
	mock\estvoyage\statsd as mockOfStatsd
;

class dataConsumerWithMtu extends units\test
{
	function beforeTestMethod($method)
	{
		require_once 'mock/net/mtu.php';
	}

	function testClass()
	{
		$this->testedClass
			->isFinal
		;
	}

	function testStatsdMetricProviderIs()
	{
		$this
			->given(
				$statsdMetricTemplate = new mockOfStatsd\metric\template
			)
			->if(
				$this->newTestedInstance(new mockOfData\consumer, new net\mtu(rand(0, PHP_INT_MAX)))
			)
			->then
				->object($this->testedInstance->statsdMetricTemplateIs($statsdMetricTemplate))->isTestedInstance
				->mock($statsdMetricTemplate)
					->receive('statsdMetricConsumerIs')
						->withArguments($this->testedInstance)
							->once
		;
	}

	function testNewDataFromStatsdMetricTemplate()
	{
		$this
			->given(
				$data = new data\data('a'),
				$mtu = new net\mtu(2),
				$dataConsumer = new mockOfData\consumer,
				$statsdMetricTemplate = new mockOfStatsd\metric\template
			)

			->if(
				$this->newTestedInstance($dataConsumer, $mtu)
			)
			->then
				->object($this->testedInstance->newDataFromStatsdMetricTemplate($data, $statsdMetricTemplate))->isTestedInstance
				->mock($dataConsumer)
					->receive('newData')
						->withArguments($data)
							->once

			->given(
				$dataExceedMtu = new data\data('bbb')
			)
			->if(
				$this->testedInstance->newDataFromStatsdMetricTemplate($dataExceedMtu, $statsdMetricTemplate)
			)
			->then
				->mock($dataConsumer)
					->receive('newData')
						->withArguments($dataExceedMtu)
							->never
				->mock($statsdMetricTemplate)
					->receive('mtuOfStatsdMetricConsumerIs')
						->withArguments($this->testedInstance, $mtu)
							->once
		;
	}
}
