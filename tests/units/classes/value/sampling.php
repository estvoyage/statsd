<?php

namespace estvoyage\statsd\tests\units\value;

require __DIR__ . '/../../runner.php';

use
	mock\estvoyage\statsd\world as statsd
;

class sampling extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\value\sampling')
		;
	}

	function test__construct()
	{
		$this
			->exception(function() { $this->newTestedInstance(uniqid()); })
				->isInstanceOf('estvoyage\statsd\value\sampling\exception')
				->hasMessage('Sampling must be a float greater than 0.0')
		;
	}

	function testApplyTo()
	{
		$this
			->given(
				$value = new statsd\value,
				$callback = function() {}
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->applyTo($value, $callback))->isTestedInstance
				->mock($value)->call('applySampling')->withIdenticalArguments(1, $callback)->once

			->if(
				$this->newTestedInstance($samplingValue = rand(0, PHP_INT_MAX))
			)
			->then
				->object($this->testedInstance->applyTo($value, $callback))->isTestedInstance
				->mock($value)->call('applySampling')->withIdenticalArguments($samplingValue, $callback)->once
		;
	}

	function testSend()
	{
		$this
			->given(
				$value = uniqid(),
				$type = uniqid(),
				$bucket = new statsd\bucket,
				$connection = new statsd\connection
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->send($bucket, $value, $type, $connection))->isTestedInstance
				->mock($bucket)->call('send')->withArguments($value, $type, 1, $connection, null)->once
			->if(
				$this->newTestedInstance($sampling = rand(0, PHP_INT_MAX))
			)
			->then
				->object($this->testedInstance->send($bucket, $value, $type, $connection))->isTestedInstance
				->mock($bucket)->call('send')->withArguments($value, $type, $sampling, $connection, null)->once

				->object($this->testedInstance->send($bucket, $value, $type, $connection, $timeout = rand(0, PHP_INT_MAX)))->isTestedInstance
				->mock($bucket)->call('send')->withArguments($value, $type, $sampling, $connection, $timeout)->once
		;
	}
}
