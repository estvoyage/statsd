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

	function testSend()
	{
		$this
			->given(
				$bucket = new statsd\bucket,
				$sampling = new statsd\value\sampling,
				$timeout = new statsd\connection\socket\timeout,
				$connection = new statsd\connection
			)
			->if(
				$this->newTestedInstance($value = uniqid(), $type = uniqid())
			)
			->then
				->object($this->testedInstance->send($bucket, $connection))->isTestedInstance
				->mock($bucket)->call('send')->withArguments($value . '|' . $type, $connection, new sampling, null)->once

				->object($this->testedInstance->send($bucket, $connection, $sampling))->isTestedInstance
				->mock($bucket)->call('send')->withIdenticalArguments($value . '|' . $type, $connection, $sampling, null)->once

				->object($this->testedInstance->send($bucket, $connection, $sampling, $timeout))->isTestedInstance
				->mock($bucket)->call('send')->withIdenticalArguments($value . '|' . $type, $connection, $sampling, $timeout)->once
		;
	}
}
