<?php

namespace estvoyage\statsd\tests\units\value;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\value,
	mock\estvoyage\statsd\world as statsd
;

class timing extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\value')
		;
	}

	function test__construct()
	{
		$this
			->exception(function() { $this->newTestedInstance('x'); })
				->isInstanceOf('estvoyage\statsd\value\timing\exception')
				->hasMessage('Timing must be an integer greater than or equal to 0')

			->exception(function() { $this->newTestedInstance(- rand(PHP_INT_MAX, -1)); })
				->isInstanceOf('estvoyage\statsd\value\timing\exception')
				->hasMessage('Timing must be an integer greater than or equal to 0')

			->exception(function() { $this->newTestedInstance('1timing'); })
				->isInstanceOf('estvoyage\statsd\value\timing\exception')
				->hasMessage('Timing must be an integer greater than or equal to 0')

			->exception(function() { $this->newTestedInstance(3.14); })
				->isInstanceOf('estvoyage\statsd\value\timing\exception')
				->hasMessage('Timing must be an integer greater than or equal to 0')
		;
	}

	function testWriteOn()
	{
		$this
			->given(
				$connection = new statsd\connection,
				$callback = function($connection) use (& $connectionAfterWriteOn) { $connectionAfterWriteOn = $connection; },
				$value = rand(0, PHP_INT_MAX),
				$sampling = new statsd\value\sampling
			)
			->if(
				$this->calling($connection)->write = function($data, $callback) use (& $connectionWithValueWrited) { $callback($connectionWithValueWrited); },
				$connectionWithValueWrited = new statsd\connection,

				$this->calling($connectionWithValueWrited)->write = function($data, $callback) use (& $connectionWithSamplingWrited) { $callback($connectionWithSamplingWrited); },
				$connectionWithSamplingWrited = new statsd\connection,

				$this->calling($connectionWithSamplingWrited)->endPacket = function($callback) use (& $connectionAfterEndPacket) { $callback($connectionAfterEndPacket); },
				$connectionAfterEndPacket = new statsd\connection,

				$this->newTestedInstance($value)
			)
			->then
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($connection)->call('write')->withArguments($value . '|t')->once
				->mock($connectionWithValueWrited)->call('write')->withIdenticalArguments('')->once
				->mock($connectionWithSamplingWrited)->call('endPacket')->withIdenticalArguments($callback)->once
				->object($connectionAfterWriteOn)->isIdenticalTo($connectionAfterEndPacket)

			->if(
				$this->calling($sampling)->writeOn = function($connection, $callback) use ($connectionWithSamplingWrited) { $connection->write('|@1.1', $callback); },
				$this->newTestedInstance($value, $sampling)
			)
			->then
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($connection)->call('write')->withArguments($value . '|t')->twice
				->mock($connectionWithValueWrited)->call('write')->withIdenticalArguments('|@1.1')->once
				->mock($connectionWithSamplingWrited)->call('endPacket')->withIdenticalArguments($callback)->twice
				->object($connectionAfterWriteOn)->isIdenticalTo($connectionAfterEndPacket)
		;
	}
}
