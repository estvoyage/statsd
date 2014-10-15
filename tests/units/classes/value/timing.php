<?php

namespace estvoyage\statsd\tests\units\value;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\value,
	mock\estvoyage\statsd\world as statsd
;

class timing extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->extends('estvoyage\statsd\value')
		;
	}

	function test__construct()
	{
		$this
			->exception(function() { $this->newTestedInstance('x'); })
				->isInstanceOf('estvoyage\statsd\value\timing\exception')
				->hasMessage('Timing must be an integer greater than or equal to 0')

			->exception(function() { $this->newTestedInstance(- rand(PHP_INT_MAX, -1)); })
				->isInstanceOf('estvoyage\statsd\value\timing\exception')
				->hasMessage('Timing must be an integer greater than or equal to 0')

			->exception(function() { $this->newTestedInstance('1timing'); })
				->isInstanceOf('estvoyage\statsd\value\timing\exception')
				->hasMessage('Timing must be an integer greater than or equal to 0')

			->exception(function() { $this->newTestedInstance(3.14); })
				->isInstanceOf('estvoyage\statsd\value\timing\exception')
				->hasMessage('Timing must be an integer greater than or equal to 0')
		;
	}

	function testSend()
	{
		$this
			->given(
				$bucket = new statsd\bucket,
				$connection = new statsd\connection
			)
			->if(
				$this->newTestedInstance(0)
			)
			->then
				->object($this->testedInstance->send($bucket, $connection))->isTestedInstance
				->mock($bucket)->call('send')->withArguments(0, 't', 1, $connection, null)->once

			->if(
				$this->newTestedInstance($value = rand(1, PHP_INT_MAX), $sampling = new statsd\value\sampling),
				$this->calling($sampling)->applyTo = function($value, $callback) use (& $samplingValue) { $this->testedInstance->applySampling($samplingValue = uniqid(), $callback); }
			)
			->then
				->object($this->testedInstance->send($bucket, $connection))->isTestedInstance
				->mock($bucket)->call('send')->withIdenticalArguments($value, 't', $samplingValue, $connection, null)->once
		;
	}
}
