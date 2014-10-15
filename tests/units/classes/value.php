<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd\value\sampling,
	mock\estvoyage\statsd\world as statsd
;

class value extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\value')
		;
	}

	function testUseSampling()
	{
	}

	function testSend()
	{
		$this
			->given(
				$bucket = new statsd\bucket,
				$connection = new statsd\connection
			)
			->if(
				$this->newTestedInstance($value = uniqid(), $type = uniqid())
			)
			->then
				->object($this->testedInstance->send($bucket, $connection))->isTestedInstance
				->mock($bucket)->call('send')->withArguments($value, $type, 1, $connection, null)->once

			->if(
				$this->newTestedInstance($value = uniqid(), $type = uniqid(), $sampling = new statsd\value\sampling),
				$this->calling($sampling)->applyTo = function($value, $callback) use (& $samplingValue) { $this->testedInstance->applySampling($samplingValue = uniqid(), $callback); }
			)
			->then
				->object($this->testedInstance->send($bucket, $connection))->isTestedInstance
				->mock($bucket)->call('send')->withIdenticalArguments($value, $type, $samplingValue, $connection, null)->once

				->object($this->testedInstance->send($bucket, $connection, $timeout = uniqid()))->isTestedInstance
				->mock($bucket)->call('send')->withArguments($value, $type, $samplingValue, $connection, $timeout)->once
		;
	}
}
