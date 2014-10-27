<?php

namespace estvoyage\statsd\tests\units\value;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\value,
	mock\estvoyage\statsd\world as statsd
;

class gauge extends \atoum
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
				->hasMessage('Gauge must be a number')
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

				$this->calling($connectionWithSamplingWrited)->endMetric = function($callback) use (& $connectionAfterEndMetric) { $callback($connectionAfterEndMetric); },
				$connectionAfterEndMetric = new statsd\connection,

				$this->newTestedInstance($value)
			)
			->then
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($connection)->call('write')->withArguments($value . '|g')->once
				->mock($connectionWithValueWrited)->call('write')->withIdenticalArguments('')->once
				->mock($connectionWithSamplingWrited)->call('endMetric')->withIdenticalArguments($callback)->once
				->object($connectionAfterWriteOn)->isIdenticalTo($connectionAfterEndMetric)

			->if(
				$this->newTestedInstance('+10')
			)
			->then
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($connection)->call('write')->withIdenticalArguments('+10|g')->once
				->mock($connectionWithValueWrited)->call('write')->withIdenticalArguments('')->twice
				->mock($connectionWithSamplingWrited)->call('endMetric')->withIdenticalArguments($callback)->twice
				->object($connectionAfterWriteOn)->isIdenticalTo($connectionAfterEndMetric)

			->if(
				$this->newTestedInstance('-10')
			)
			->then
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($connection)->call('write')->withIdenticalArguments('-10|g')->once
				->mock($connectionWithValueWrited)->call('write')->withIdenticalArguments('')->thrice
				->mock($connectionWithSamplingWrited)->call('endMetric')->withIdenticalArguments($callback)->thrice
				->object($connectionAfterWriteOn)->isIdenticalTo($connectionAfterEndMetric)

			->if(
				$this->calling($sampling)->writeOn = function($connection, $callback) use ($connectionWithSamplingWrited) { $connection->write('|@1.1', $callback); },
				$this->newTestedInstance($value, $sampling)
			)
			->then
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($connection)->call('write')->withArguments($value . '|g')->twice
				->mock($connectionWithValueWrited)->call('write')->withIdenticalArguments('|@1.1')->once
				->mock($connectionWithSamplingWrited)->call('endMetric')->withIdenticalArguments($callback)->{4}
				->object($connectionAfterWriteOn)->isIdenticalTo($connectionAfterEndMetric)
		;
	}
}

