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
		;
	}

	function testWriteOn()
	{
		$this
			->given(
				$connection = new statsd\connection,

				$this->calling($connection)->startPacket = function($callback) use (& $connectionAfterStartPacket) { $callback($connectionAfterStartPacket); },
				$connectionAfterStartPacket = new statsd\connection,

				$this->calling($connectionAfterStartPacket)->writeMetric = function($metric, $callback) use (& $connectionAfterMetricWrited) { $callback($connectionAfterMetricWrited); },
				$connectionAfterMetricWrited = new statsd\connection,

				$this->calling($connectionAfterMetricWrited)->endPacket = function($callback) use (& $connectionAfterEndPacket) { $callback($connectionAfterEndPacket); },
				$connectionAfterEndPacket = new statsd\connection,

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
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($connection)->call('startPacket')->twice
				->mock($connectionAfterStartPacket)->call('endPacket')->twice

				->object($packetWithMetric->writeOn($connection, $callback))->isIdenticalTo($packetWithMetric)
				->mock($connection)->call('startPacket')->thrice
				->mock($connectionAfterStartPacket)->call('writeMetric')->withIdenticalArguments($metric)->once
				->mock($connectionAfterMetricWrited)->call('endPacket')->once
				->object($connectionWrited)->isIdenticalTo($connectionAfterEndPacket)
		;
	}

	function testAdd()
	{
		$this
			->given(
				$metric = new statsd\metric,
				$callback = function($packet) use (& $packetWithMetric) { $packetWithMetric = $packet; }
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->add($metric, $callback))->isTestedInstance
				->object($packetWithMetric)
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
		;
	}
}
