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

	function testWriteOn()
	{
		$this
			->given(
				$bucket = uniqid(),
				$callback = function($connection) use (& $connectionAfterWriteOn) { $connectionAfterWriteOn = $connection; },
				$connection = new statsd\connection
			)
			->if(
				$this->calling($connection)->startPacket = function($callback) use (& $connectionAfterStartPacket) { $callback($connectionAfterStartPacket); },
				$connectionAfterStartPacket = new statsd\connection,

				$this->calling($connectionAfterStartPacket)->startMetric = function($callback) use (& $connectionAfterStartMetric) { $callback($connectionAfterStartMetric); },
				$connectionAfterStartMetric = new statsd\connection,

				$this->calling($connectionAfterStartMetric)->write = function($data, $callback) use (& $connectionWrited) { $callback($connectionWrited); },
				$connectionWrited = new statsd\connection,

				$this->newTestedInstance($bucket)
			)
			->then
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($connection)->call('startPacket')->once
				->mock($connectionAfterStartPacket)->call('startMetric')->once
				->mock($connectionAfterStartMetric)->call('write')->withIdenticalArguments($bucket . ':', $callback)->once
				->object($connectionAfterWriteOn)->isIdenticalTo($connectionWrited)
		;
	}
}
