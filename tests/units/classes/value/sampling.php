<?php

namespace estvoyage\statsd\tests\units\value;

require __DIR__ . '/../../runner.php';

use
	mock\estvoyage\statsd\world as statsd
;

class sampling extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\value\sampling')
		;
	}

	function test__construct()
	{
		$this
			->exception(function() { $this->newTestedInstance(uniqid()); })
				->isInstanceOf('estvoyage\statsd\value\sampling\exception')
				->hasMessage('Sampling must be a float greater than 0.0')
		;
	}

	function testSend()
	{
		$this
			->given(
				$connection = new statsd\connection,
				$value = uniqid(),
				$timeout = new statsd\connection\socket\timeout
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->send($value, $connection))->isTestedInstance
				->mock($connection)->call('send')->withArguments($value, null)->once

			->if(
				$this->newTestedInstance(1.1)
			)
			->then
				->object($this->testedInstance->send($value, $connection))->isTestedInstance
				->mock($connection)->call('send')->withArguments($value . '|@1.1', null)->once

			->if(
				$this->newTestedInstance(0.9)
			)
			->then
				->object($this->testedInstance->send($value, $connection))->isTestedInstance
				->mock($connection)->call('send')->withArguments($value . '|@0.9', null)->once

				->object($this->testedInstance->send($value, $connection, $timeout))->isTestedInstance
				->mock($connection)->call('send')->withArguments($value . '|@0.9', $timeout)->once
		;
	}
}
