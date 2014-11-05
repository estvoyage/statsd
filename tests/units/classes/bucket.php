<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd\tests\units,
	mock\estvoyage\statsd\world as statsd
;

class bucket extends units\test
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\bucket')
			->implements('estvoyage\statsd\world\metric\component')
			->implements('estvoyage\statsd\world\connection\data')
		;
	}

	function testWriteOn()
	{
		$this
			->given(
				$bucket = uniqid(),
				$this->calling($connection = new statsd\connection)->startPacket = $connectionAfterStartPacket = new statsd\connection,
				$this->calling($connectionAfterStartPacket)->startMetric = $connectionAfterStartMetric = new statsd\connection,
				$this->calling($connectionAfterStartMetric)->write = $connectionAfterWrite = new statsd\connection
			)
			->if(
				$this->newTestedInstance($bucket)
			)
			->then
				->object($this->testedInstance->writeOn($connection))->isIdenticalTo($connectionAfterWrite)
				->mock($connection)->call('startPacket')->once
				->mock($connectionAfterStartPacket)->call('startMetric')->once
				->mock($connectionAfterStartMetric)->call('write')->withIdenticalArguments($bucket . ':')->once
		;
	}
}
