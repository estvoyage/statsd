<?php

namespace seshat\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	mock\seshat\statsd\world as statsd
;

class client extends \atoum
{
	function testSendValue()
	{
		$this
			->given(
				$this->newTestedInstance($connection = new statsd\connection)
			)
			->if(
				$value = new statsd\value,
				$bucket = new statsd\bucket
			)
			->then
				->object($this->testedInstance->sendValue($value, $bucket))->isTestedInstance
				->mock($value)->call('send')->withIdenticalArguments($bucket, $connection, null)->once

				->object($this->testedInstance->sendValue($value, $bucket, $timeout = uniqid()))->isTestedInstance
				->mock($value)->call('send')->withIdenticalArguments($bucket, $connection, $timeout)->once
		;
	}
}
