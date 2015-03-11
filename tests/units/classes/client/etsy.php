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
		require_once 'mock/net/mtu.php';
	}

	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\client')
		;
	}

	function testStatsdMetricProviderIs()
	{
		$this
			->given(
				$dataConsumer = new mockOfData\consumer,
				$mtu = new net\mtu(rand(0, PHP_INT_MAX)),
				$this->newTestedInstance($dataConsumer, $mtu)
			)
			->if(
				$statsdMetricProvider = new mockOfStatsd\metric\provider
			)
			->then
				->object($this->testedInstance->statsdMetricProviderIs($statsdMetricProvider))
					->isTestedInstance
				->mock($statsdMetricProvider)
					->receive('statsdMetricFactoryIs')
						->withArguments(new metric\factory\etsy(new metric\consumer($dataConsumer, $mtu)))->once
		;
	}
}
