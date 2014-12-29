<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\packet,
	mock\estvoyage\statsd\world as mock
;

class client extends test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\world\client')
		;
	}

	function testEndOfCode()
	{
		require_once 'mock/statsd/metric.php';

		$this
			->given(
				$connection = new mock\connection,
				$metric1 = new \mock\estvoyage\statsd\metric(uniqid()),
				$metric2 = new \mock\estvoyage\statsd\metric(uniqid()),
				$metric3 = new \mock\estvoyage\statsd\metric(uniqid()),
				$packetBuilder = new mock\packet\builder
			)

			->if(
				$this->newTestedInstance($connection)
			)
			->then
				->object($this->testedInstance->endOfCode())->isTestedInstance
				->mock($connection)->call('packetShouldBeSend')->withArguments(new packet)->once

				->object($this->testedInstance->codeHasGeneratedMetrics($metric1)->endOfCode())->isTestedInstance
				->mock($connection)->call('packetShouldBeSend')->withArguments(new packet($metric1))->once

				->object($this->testedInstance->codeHasGeneratedMetrics($metric1, $metric2, $metric3)->endOfCode())->isTestedInstance
				->mock($connection)->call('packetShouldBeSend')->withArguments(new packet($metric1, $metric2, $metric3))->once

			->if(
				$this->newTestedInstance($connection, $packetBuilder)
			)
			->then
				->object($this->testedInstance->endOfCode())->isTestedInstance
				->mock($packetBuilder)->call('packetShouldBeSendOn')->withIdenticalArguments($connection)->once
		;
	}

	function testCodeHasGeneratedMetrics()
	{
		require_once 'mock/statsd/metric.php';

		$this
			->given(
				$metric1 = new \mock\estvoyage\statsd\metric(uniqid()),
				$metric2 = new \mock\estvoyage\statsd\metric(uniqid()),
				$metric3 = new \mock\estvoyage\statsd\metric(uniqid()),
				$packetBuilder = new mock\packet\builder
			)

			->if(
				$this->newTestedInstance(new mock\connection)
			)
			->then
				->object($this->testedInstance->codeHasGeneratedMetrics($metric1))->isTestedInstance
				->object($this->testedInstance->codeHasGeneratedMetrics($metric1, $metric2))->isTestedInstance
				->object($this->testedInstance->codeHasGeneratedMetrics($metric1, $metric2, $metric3))->isTestedInstance

			->if(
				$this->newTestedInstance(new mock\connection, $packetBuilder)
			)
			->then
				->object($this->testedInstance->codeHasGeneratedMetrics($metric1))->isTestedInstance
				->mock($packetBuilder)->call('useMetrics')->withIdenticalArguments($metric1)->once

				->object($this->testedInstance->codeHasGeneratedMetrics($metric1, $metric2))->isTestedInstance
				->mock($packetBuilder)->call('useMetrics')->withIdenticalArguments($metric1, $metric2)->once

				->object($this->testedInstance->codeHasGeneratedMetrics($metric1, $metric2, $metric3))->isTestedInstance
				->mock($packetBuilder)->call('useMetrics')->withIdenticalArguments($metric1, $metric2, $metric3)->once
		;
	}
}
