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

	function testWriteOn()
	{
		$this
			->given(
				$connection = new statsd\connection,
				$callback = function($connection) use (& $connectionAfterWriteOn) { $connectionAfterWriteOn = $connection; },
				$value = uniqid(),
				$type = uniqid(),
				$sampling = new statsd\value\sampling
			)
			->if(
				$this->calling($connection)->write = function($data, $callback) use (& $connectionWithValueWrited) { $callback($connectionWithValueWrited); },
				$connectionWithValueWrited = new statsd\connection,

				$this->calling($connectionWithValueWrited)->write = function($data, $callback) use (& $connectionWithSamplingWrited) { $callback($connectionWithSamplingWrited); },
				$connectionWithSamplingWrited = new statsd\connection,

				$this->calling($connectionWithSamplingWrited)->endMetric = function($callback) use (& $connectionAfterEndMetric) { $callback($connectionAfterEndMetric); },
				$connectionAfterEndMetric = new statsd\connection,

				$this->calling($connectionAfterEndMetric)->endPacket = function($callback) use (& $connectionAfterEndPacket) { $callback($connectionAfterEndPacket); },
				$connectionAfterEndPacket = new statsd\connection,

				$this->newTestedInstance($value, $type)
			)
			->then
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($connection)->call('write')->withArguments($value . '|' . $type)->once
				->mock($connectionWithValueWrited)->call('write')->withIdenticalArguments('')->once
				->mock($connectionWithSamplingWrited)->call('endMetric')->once
				->mock($connectionAfterEndMetric)->call('endPacket')->withIdenticalArguments($callback)->once
				->object($connectionAfterWriteOn)->isIdenticalTo($connectionAfterEndPacket)

			->if(
				$this->calling($sampling)->writeOn = function($connection, $callback) use ($connectionWithSamplingWrited) { $connection->write('|@1.1', $callback); },
				$this->newTestedInstance($value, $type, $sampling)
			)
			->then
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($connection)->call('write')->withArguments($value . '|' . $type)->twice
				->mock($connectionWithValueWrited)->call('write')->withIdenticalArguments('|@1.1')->once
				->mock($connectionWithSamplingWrited)->call('endMetric')->twice
				->mock($connectionAfterEndMetric)->call('endPacket')->withIdenticalArguments($callback)->twice
				->object($connectionAfterWriteOn)->isIdenticalTo($connectionAfterEndPacket)
		;
	}
}
