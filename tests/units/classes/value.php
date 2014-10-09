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

	function test__construct()
	{
		$this
			->exception(function() { $this->newTestedInstance(uniqid(), uniqid(), 'foo'); })
				->isInstanceOf('estvoyage\statsd\value\sampling\exception')
				->hasMessage('Sampling must be a float greater than 0.0')
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
				$this->newTestedInstance($value = uniqid(), $type = uniqid(), 1)
			)
			->then
				->object($this->testedInstance->send($bucket, $connection))->isTestedInstance
				->mock($bucket)->call('sendWithSampling')->withArguments($value, $type, new sampling, $connection, null)->once

				->object($this->testedInstance->send($bucket, $connection, $timeout = uniqid()))->isTestedInstance
				->mock($bucket)->call('sendWithSampling')->withArguments($value, $type, new sampling, $connection, $timeout)->once

			->if(
				$this->newTestedInstance($value = uniqid(), $type = uniqid(), $sampling = rand(1, PHP_INT_MAX))
			)
			->then
				->object($this->testedInstance->send($bucket, $connection))->isTestedInstance
				->mock($bucket)->call('sendWithSampling')->withArguments($value, $type, new sampling($sampling), $connection, null)->once

				->object($this->testedInstance->send($bucket, $connection, $timeout = uniqid()))->isTestedInstance
				->mock($bucket)->call('sendWithSampling')->withArguments($value, $type, new sampling($sampling), $connection, $timeout)->once
		;
	}
}
