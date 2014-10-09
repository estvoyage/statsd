<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	mock\estvoyage\statsd\world as statsd
;

class bucket extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\bucket')
		;
	}

	function testSend()
	{
		$this
			->given(
				$connection = new statsd\connection,
				$value = uniqid(),
				$type = uniqid(),
				$sampleRate = uniqid(),
				$timeout = uniqid(),
				$bucket = uniqid()
			)
			->if(
				$this->newTestedInstance($bucket)
			)
			->then
				->object($this->testedInstance->send($value, $type, $sampleRate, $connection))->isTestedInstance
				->mock($connection)->call('send')->withArguments($bucket, $value, $type, $sampleRate, null)->once

				->object($this->testedInstance->send($value, $type, $sampleRate, $connection, $timeout))->isTestedInstance
				->mock($connection)->call('send')->withArguments($bucket, $value, $type, $sampleRate, $timeout)->once
		;
	}

	function testSendWithSampling()
	{
		$this
			->given(
				$connection = new statsd\connection,
				$sampling = new statsd\value\sampling,
				$value = uniqid(),
				$type = uniqid(),
				$timeout = uniqid(),
				$bucket = uniqid()
			)
			->if(
				$this->newTestedInstance($bucket)
			)
			->then
				->object($this->testedInstance->sendWithSampling($value, $type, $sampling, $connection))->isTestedInstance
				->mock($sampling)->call('send')->withArguments($this->testedInstance, $value, $type, $connection, null)->once

				->object($this->testedInstance->sendWithSampling($value, $type, $sampling, $connection, $timeout))->isTestedInstance
				->mock($sampling)->call('send')->withArguments($this->testedInstance, $value, $type, $connection, $timeout)->once
		;
	}
}
