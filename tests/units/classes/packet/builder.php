<?php

namespace estvoyage\statsd\tests\units\packet;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric,
	estvoyage\statsd\packet,
	mock\estvoyage\statsd\world as statsd
;

class builder extends units\test
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\packet\builder')
		;
	}

	function testUseMetrics()
	{
		require_once 'mock/statsd/metric.php';

		$this
			->given(
				$metric1 = new metric,
				$metric2 = new metric
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->useMetrics($metric1))->isTestedInstance
				->object($this->testedInstance->useMetrics($metric1, $metric2))->isTestedInstance
		;
	}

	function testPacketShouldBeSendOn()
	{
		require_once 'mock/statsd/metric.php';

		$this
			->given(
				$metric1 = new metric(uniqid()),
				$metric2 = new metric(uniqid()),
				$connection = new statsd\connection
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->packetShouldBeSendOn($connection))->isTestedInstance
				->mock($connection)->call('packetShouldBeSend')->withArguments(new packet)->once

			->if(
				$this->testedInstance->useMetrics($metric1)
			)
			->then
				->object($this->testedInstance->packetShouldBeSendOn($connection))->isTestedInstance
				->mock($connection)->call('packetShouldBeSend')->withArguments(new packet($metric1))->once

			->if(
				$this->testedInstance->useMetrics($metric1, $metric2)
			)
			->then
				->object($this->testedInstance->packetShouldBeSendOn($connection))->isTestedInstance
				->mock($connection)->call('packetShouldBeSend')->withArguments(new packet($metric1, $metric2))->once

			->if(
				$this->newTestedInstance($metric1, $metric2)
			)
			->then
				->object($this->testedInstance->packetShouldBeSendOn($connection))->isTestedInstance
				->mock($connection)->call('packetShouldBeSend')->withArguments(new packet($metric1, $metric2))->twice
		;
	}
}
