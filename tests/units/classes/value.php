<?php

namespace seshat\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	mock\seshat\statsd\world as statsd
;

class value extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('seshat\statsd\world\value')
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
				$this->newTestedInstance($value = uniqid(), $type = uniqid(), $sampleRate = uniqid())
			)
			->then
				->object($this->testedInstance->send($bucket, $connection))->isTestedInstance
				->mock($bucket)->call('send')->withArguments($value, $type, $sampleRate, $connection, null)->once

				->object($this->testedInstance->send($bucket, $connection, $timeout = uniqid()))->isTestedInstance
				->mock($bucket)->call('send')->withArguments($value, $type, $sampleRate, $connection, $timeout)->once
		;
	}
}
