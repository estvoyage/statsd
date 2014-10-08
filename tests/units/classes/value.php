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

	function test__construct()
	{
		$this
			->exception(function() { $this->newTestedInstance(uniqid(), uniqid(), uniqid()); })
				->isInstanceOf('seshat\statsd\value\exception')
				->hasMessage('Sample rate must be a float greater than 0.0')
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
				->mock($bucket)->call('send')->withArguments($value, $type, 1, $connection, null)->once

				->object($this->testedInstance->send($bucket, $connection, $timeout = uniqid()))->isTestedInstance
				->mock($bucket)->call('send')->withArguments($value, $type, 1, $connection, $timeout)->once
		;
	}
}
