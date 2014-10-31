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
			->implements('estvoyage\statsd\world\metric\component')
			->implements('estvoyage\statsd\world\connection\data')
		;
	}

	function testWriteOn()
	{
		$this
			->given(
				$value = uniqid(),
				$type = uniqid(),
				$sampling = new statsd\value\sampling,

				$this->calling($connection = new statsd\connection)->write = $connectionWithValueWrited = new statsd\connection,
				$this->calling($connectionWithValueWrited)->writeData = $connectionWithSamplingWrited = new statsd\connection,
				$this->calling($connectionWithSamplingWrited)->endMetric = $connectionAfterEndMetric = new statsd\connection,
				$this->calling($connectionAfterEndMetric)->endPacket = $connectionAfterEndPacket = new statsd\connection
			)
			->if(
				$this->newTestedInstance($value, $type)
			)
			->then
				->object($this->testedInstance->writeOn($connection))->isIdenticalTo($connectionAfterEndPacket)
				->mock($connection)->call('write')->withArguments($value . '|' . $type)->once
				->mock($connectionWithValueWrited)->call('writeData')->withArguments(new sampling)->once
				->mock($connectionWithSamplingWrited)->call('endMetric')->once
				->mock($connectionAfterEndMetric)->call('endPacket')->once

			->if(
				$this->newTestedInstance($value, $type, $sampling)
			)
			->then
				->object($this->testedInstance->writeOn($connection))->isIdenticalTo($connectionAfterEndPacket)
				->mock($connection)->call('write')->withArguments($value . '|' . $type)->twice
				->mock($connectionWithValueWrited)->call('writeData')->withIdenticalArguments($sampling)->once
				->mock($connectionWithSamplingWrited)->call('endMetric')->twice
				->mock($connectionAfterEndMetric)->call('endPacket')->twice
		;
	}
}
