<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	mock\estvoyage\statsd\world as statsd
;

class client extends \atoum
{
	function testSend()
	{
		$this
			->given(
				$this->newTestedInstance($connection = new statsd\connection)
			)
			->if(
				$bucket = new statsd\bucket,
				$value = new statsd\value,
				$sampling = new statsd\value\sampling,
				$timeout = new statsd\connection\socket\timeout
			)
			->then
				->object($this->testedInstance->send($bucket, $value))->isTestedInstance
				->mock($value)->call('send')->withIdenticalArguments($bucket, $connection, null, null)->once

				->object($this->testedInstance->send($bucket, $value, $sampling))->isTestedInstance
				->mock($value)->call('send')->withIdenticalArguments($bucket, $connection, $sampling, null)->once

				->object($this->testedInstance->send($bucket, $value, $sampling, $timeout))->isTestedInstance
				->mock($value)->call('send')->withIdenticalArguments($bucket, $connection, $sampling, $timeout)->once
		;
	}
}
