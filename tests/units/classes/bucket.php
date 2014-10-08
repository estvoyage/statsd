<?php

namespace seshat\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	mock\seshat\statsd\world as statsd
;

class bucket extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('seshat\statsd\world\bucket')
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
}
