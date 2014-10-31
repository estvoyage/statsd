<?php

namespace estvoyage\statsd\tests\units\value;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\value,
	mock\estvoyage\statsd\world as statsd
;

class gauge extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\value')
		;
	}

	function test__construct()
	{
		$this
			->exception(function() { $this->newTestedInstance('x'); })
				->isInstanceOf('estvoyage\statsd\value\timing\exception')
				->hasMessage('Gauge must be a number')
		;
	}

	function testWriteOn()
	{
		$this
			->given(
				$callback = function($connection) use (& $connectionAfterWriteOn) { $connectionAfterWriteOn = $connection; },
				$value = rand(0, PHP_INT_MAX),
				$sampling = new statsd\value\sampling,

				$this->calling($connection = new statsd\connection())->write = $connectionWithValueWrited = new statsd\connection,
				$this->calling($connectionWithValueWrited)->writeData = $connectionWithSamplingWrited = new statsd\connection,
				$this->calling($connectionWithSamplingWrited)->endMetric = $connectionAfterEndMetric = new statsd\connection,
				$this->calling($connectionAfterEndMetric)->endPacket = $connectionAfterEndPacket = new statsd\connection
			)
			->if(
				$this->newTestedInstance($value)
			)
			->then
				->object($this->testedInstance->writeOn($connection))->isIdenticalTo($connectionAfterEndPacket)
				->mock($connection)->call('write')->withArguments($value . '|g')->once
				->mock($connectionWithValueWrited)->call('writeData')->withArguments(new value\sampling)->once
				->mock($connectionWithSamplingWrited)->call('endMetric')->once
				->mock($connectionAfterEndMetric)->call('endPacket')->once

			->if(
				$this->newTestedInstance('+10')
			)
			->then
				->object($this->testedInstance->writeOn($connection))->isIdenticalTo($connectionAfterEndPacket)
				->mock($connection)->call('write')->withIdenticalArguments('+10|g')->once
				->mock($connectionWithValueWrited)->call('writeData')->withArguments(new value\sampling)->twice
				->mock($connectionWithSamplingWrited)->call('endMetric')->twice
				->mock($connectionAfterEndMetric)->call('endPacket')->twice

			->if(
				$this->newTestedInstance('-10')
			)
			->then
				->object($this->testedInstance->writeOn($connection))->isIdenticalTo($connectionAfterEndPacket)
				->mock($connection)->call('write')->withIdenticalArguments('-10|g')->once
				->mock($connectionWithValueWrited)->call('writeData')->withArguments(new value\sampling)->thrice
				->mock($connectionWithSamplingWrited)->call('endMetric')->thrice
				->mock($connectionAfterEndMetric)->call('endPacket')->thrice

			->if(
				$this->newTestedInstance($value, $sampling)
			)
			->then
				->object($this->testedInstance->writeOn($connection))->isIdenticalTo($connectionAfterEndPacket)
				->mock($connection)->call('write')->withArguments($value . '|g')->twice
				->mock($connectionWithValueWrited)->call('writeData')->withIdenticalArguments($sampling)->once
				->mock($connectionWithSamplingWrited)->call('endMetric')->{4}
				->mock($connectionAfterEndMetric)->call('endPacket')->{4}
		;
	}
}
