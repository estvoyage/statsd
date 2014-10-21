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
			->exception(function() { $this->newTestedInstance(-0.1); })
				->isInstanceOf('estvoyage\statsd\value\sampling\exception')
				->hasMessage('Sampling must be a float greater than 0.0')
		;
	}

	function testWriteOn()
	{
		$this
			->given(
				$callback = function($connection) use (& $connectionAfterWriteOn) { $connectionAfterWriteOn = $connection; },

				$connection = new statsd\connection,
				$this->calling($connection)->write = function($data, $callback) use (& $connectionWrited) { $callback($connectionWrited); },

				$connectionWrited = new statsd\connection
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($connection)->call('write')->never

			->if(
				$this->newTestedInstance(1.1)
			)
			->then
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($connection)->call('write')->withIdenticalArguments('|@1.1', $callback)->once
				->object($connectionAfterWriteOn)->isIdenticalTo($connectionWrited)

			->if(
				$this->newTestedInstance(0.9)
			)
			->then
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($connection)->call('write')->withIdenticalArguments('|@0.9', $callback)->once
				->object($connectionAfterWriteOn)->isIdenticalTo($connectionWrited)
		;
	}
}
