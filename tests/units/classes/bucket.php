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
				$sampling = new statsd\value\sampling,
				$timeout = new statsd\connection\socket\timeout
			)
			->if(
				$this->newTestedInstance($bucket = uniqid())
			)
			->then
				->object($this->testedInstance->send($value, $connection, $sampling))->isTestedInstance
				->mock($sampling)->call('send')->withArguments($bucket . ':' . $value, $connection, null)->once

				->object($this->testedInstance->send($value, $connection, $sampling, $timeout))->isTestedInstance
				->mock($sampling)->call('send')->withIdenticalArguments($bucket . ':' . $value, $connection, $timeout)->once
		;
	}
}
