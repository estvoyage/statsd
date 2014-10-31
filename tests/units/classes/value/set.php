<?php

namespace estvoyage\statsd\tests\units\value;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\value,
	mock\estvoyage\statsd\world as statsd
;

class set extends \atoum
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
				->hasMessage('Set must be a number')
		;
	}

	function testWriteOn()
	{
		$this
			->given(
				$value = rand(- PHP_INT_MAX, PHP_INT_MAX),
				$sampling = new statsd\value\sampling,

				$this->calling($connection = new statsd\connection)->write = $connectionWithValueWrited = new statsd\connection,
				$this->calling($connectionWithValueWrited)->writeData = $connectionWithSamplingWrited = new statsd\connection,
				$this->calling($connectionWithSamplingWrited)->endMetric = $connectionAfterEndMetric = new statsd\connection,
				$this->calling($connectionAfterEndMetric)->endPacket = $connectionAfterEndPacket = new statsd\connection
			)
			->if(
				$this->newTestedInstance($value)
			)
			->then
				->object($this->testedInstance->writeOn($connection))->isIdenticalTo($connectionAfterEndPacket)
				->mock($connection)->call('write')->withArguments($value . '|s')->once
				->mock($connectionWithValueWrited)->call('writeData')->withArguments(new value\sampling)->once
				->mock($connectionWithSamplingWrited)->call('endMetric')->once
				->mock($connectionAfterEndMetric)->call('endPacket')->once

			->if(
				$this->newTestedInstance($value, $sampling)
			)
			->then
				->object($this->testedInstance->writeOn($connection))->isIdenticalTo($connectionAfterEndPacket)
				->mock($connection)->call('write')->withArguments($value . '|s')->twice
				->mock($connectionWithValueWrited)->call('writeData')->withIdenticalArguments($sampling)->once
				->mock($connectionWithSamplingWrited)->call('endMetric')->twice
				->mock($connectionAfterEndMetric)->call('endPacket')->twice
		;
	}
}
