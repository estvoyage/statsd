<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\client as testedClass,
	estvoyage\statsd\packet,
	mock\estvoyage\statsd\world as mock
;

class client extends test
{
	function beforeTestMethod($method)
	{
		require_once 'mock/statsd/metric.php';
	}

	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\world\client')
		;
	}

	function test__destruct()
	{
		$this
			->given(
				$connection = new mock\connection,
				$metric1 = new \mock\estvoyage\statsd\metric(uniqid()),
				$metric2 = new \mock\estvoyage\statsd\metric(uniqid()),
				$metric3 = new \mock\estvoyage\statsd\metric(uniqid())
			)

			->when(
				function() use ($connection) {
					new testedClass($connection);
				}
			)
			->then
				->mock($connection)->call('newPacket')->withArguments(new packet)->once

			->when(
				function() use ($connection, $metric1) {
					(new testedClass($connection))->newMetric($metric1);
				}
			)
			->then
				->mock($connection)->call('newPacket')->withArguments(new packet($metric1))->once

			->when(
				function() use ($connection, $metric2, $metric3) {
					(new testedClass($connection))->newMetrics($metric2, $metric3);
				}
			)
			->then
				->mock($connection)->call('newPacket')->withArguments(new packet($metric2, $metric3))->once
		;
	}

	function testNoMoreMetric()
	{
		$this
			->given(
				$connection = new mock\connection,
				$metric1 = new \mock\estvoyage\statsd\metric(uniqid()),
				$metric2 = new \mock\estvoyage\statsd\metric(uniqid()),
				$metric3 = new \mock\estvoyage\statsd\metric(uniqid())
			)

			->if(
				$this->newTestedInstance($connection)
			)
			->then
				->object($this->testedInstance->noMoreMetric())->isTestedInstance
				->mock($connection)->call('newPacket')->withArguments(new packet)->once

				->object($this->testedInstance->newMetric($metric1)->noMoreMetric())->isTestedInstance
				->mock($connection)->call('newPacket')->withArguments(new packet($metric1))->once

				->object($this->testedInstance->newMetrics($metric2, $metric3)->noMoreMetric())->isTestedInstance
				->mock($connection)->call('newPacket')->withArguments(new packet($metric2, $metric3))->once
		;
	}

	function testNewMetrics()
	{
		$this
			->given(
				$metric1 = new \mock\estvoyage\statsd\metric(uniqid()),
				$metric2 = new \mock\estvoyage\statsd\metric(uniqid()),
				$metric3 = new \mock\estvoyage\statsd\metric(uniqid())
			)

			->if(
				$this->newTestedInstance(new mock\connection)
			)
			->then
				->object($this->testedInstance->newMetrics($metric1, $metric2))->isTestedInstance
				->object($this->testedInstance->newMetrics($metric1, $metric2, $metric3))->isTestedInstance
		;
	}
}
