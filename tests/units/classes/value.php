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
				$type = uniqid()
			)
			->if(
				$this->calling($connection)->write = function($data, $callback) use (& $connectionWrited) { $callback($connectionWrited); },
				$connectionWrited = new statsd\connection,

				$this->calling($connectionWrited)->endPacket = function($callback) use (& $connectionAfterEndPacket) { $callback($connectionAfterEndPacket); },
				$connectionAfterEndPacket = new statsd\connection,

				$this->newTestedInstance($value, $type)
			)
			->then
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($connection)->call('write')->withArguments($value . '|' . $type)->once
				->mock($connectionWrited)->call('endPacket')->withIdenticalArguments($callback)->once
				->object($connectionAfterWriteOn)->isIdenticalTo($connectionAfterEndPacket)
		;
	}
}
