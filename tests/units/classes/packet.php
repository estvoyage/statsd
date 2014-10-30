<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	mock\estvoyage\statsd\world as statsd
;

class packet extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\packet')
			->implements('estvoyage\statsd\world\connection\data')
		;
	}

	function testWriteOn()
	{
		$this
			->given(
				$this->calling($connection = new statsd\connection)->startPacket = function($callback) use (& $connectionAfterStartPacket) { $callback($connectionAfterStartPacket); },
				$this->calling($connectionAfterStartPacket = new statsd\connection)->writeData = function($data, $callback) use (& $connectionAfterWriteData) { $callback($connectionAfterWriteData); },
				$this->calling($connectionAfterWriteData = new statsd\connection)->endPacket = function($callback) use (& $connectionAfterEndPacket) { $callback($connectionAfterEndPacket = new statsd\connection); },

				$metric = new statsd\metric,

				$callback = function($connection) use (& $connectionWrited) { $connectionWrited = $connection; }
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($connection)->call('startPacket')->once
				->mock($connectionAfterStartPacket)->call('endPacket')->once

			->if(
				$this->testedInstance->add($metric, function($packet) use (& $packetWithMetric) { $packetWithMetric = $packet; })
			)
			->then
				->object($packetWithMetric->writeOn($connection, $callback))->isIdenticalTo($packetWithMetric)
				->mock($connection)->call('startPacket')->twice
				->mock($connectionAfterStartPacket)->call('writeData')->withIdenticalArguments($metric)->once
				->mock($connectionAfterWriteData)->call('endPacket')->once
				->object($connectionWrited)->isIdenticalTo($connectionAfterEndPacket)
		;
	}

	function testAdd()
	{
		$this
			->given(
				$metric = new statsd\metric,
				$callback = function($packet) use (& $packetAfterAdd) { $packetAfterAdd = $packet; }
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->add($metric, $callback))->isTestedInstance
				->object($packetAfterAdd)
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
		;
	}
}
