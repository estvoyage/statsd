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

class dataConsumer extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\metric\consumer')
		;
	}

	function testStatsdMetricProviderIs()
	{
		$this
			->given(
				$statsdMetricTemplate = new mockOfStatsd\metric\template
			)
			->if(
				$this->newTestedInstance(new mockOfData\consumer)
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
				$dataConsumer = new mockOfData\consumer,
				$statsdMetricTemplate = new mockOfStatsd\metric\template
			)

			->if(
				$this->newTestedInstance($dataConsumer)
			)
			->then
				->object($this->testedInstance->newDataFromStatsdMetricTemplate($data, $statsdMetricTemplate))->isTestedInstance
				->mock($dataConsumer)
					->receive('newData')
						->withArguments($data)
							->once
		;
	}
}
