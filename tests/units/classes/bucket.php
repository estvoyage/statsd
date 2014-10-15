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
				$timeout = uniqid()
			)
			->if(
				$this->newTestedInstance($bucket = uniqid())
			)
			->then
				->object($this->testedInstance->send($value, $connection))->isTestedInstance
				->mock($connection)->call('send')->withArguments($bucket . ':' . $value, null)->once

				->object($this->testedInstance->send($value, $connection, $timeout))->isTestedInstance
				->mock($connection)->call('send')->withArguments($bucket . ':' . $value, $timeout)->once
		;
	}
}
