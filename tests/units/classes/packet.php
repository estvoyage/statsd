<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	mock\estvoyage\statsd\world as statsd,
	estvoyage\statsd\metric\gauge,
	estvoyage\statsd\metric\timing,
	estvoyage\statsd\metric\counting,
	estvoyage\statsd\metric\set
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
				$this->calling($connection = new statsd\connection)->startPacket = $connectionAfterStartPacket = new statsd\connection,
				$this->calling($connectionAfterStartPacket)->endPacket = $connectionAfterEndPacket = new statsd\connection,

				$metric = new statsd\metric
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->writeOn($connection))->isIdenticalTo($connectionAfterEndPacket)
				->mock($connection)->call('startPacket')->once
				->mock($connectionAfterStartPacket)->call('endPacket')->once

			->if(
				$this->calling($connectionAfterStartPacket)->writeData = $connectionAfterWriteData = new statsd\connection,
				$this->calling($connectionAfterWriteData)->endPacket = $connectionAfterEndPacket = new statsd\connection
			)
			->then
				->object($this->testedInstance->add($metric)->writeOn($connection))->isIdenticalTo($connectionAfterEndPacket)
				->mock($connection)->call('startPacket')->twice
				->mock($connectionAfterStartPacket)->call('writeData')->withIdenticalArguments($metric)->once
				->mock($connectionAfterWriteData)->call('endPacket')->once

			->if(
				$this->newTestedInstance([ $metric ])
			)
			->then
				->object($this->testedInstance->writeOn($connection))->isIdenticalTo($connectionAfterEndPacket)
				->mock($connection)->call('startPacket')->thrice
				->mock($connectionAfterStartPacket)->call('writeData')->withIdenticalArguments($metric)->twice
				->mock($connectionAfterWriteData)->call('endPacket')->twice
		;
	}

	function testAdd()
	{
		$this
			->given(
				$metric = new statsd\metric
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->add($metric))
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance([ $metric ]))
		;
	}

	function testAdds()
	{
		$this
			->given(
				$metric1 = new statsd\metric,
				$metric2 = new statsd\metric
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->adds([ $metric1, $metric2 ]))
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance([ $metric1, $metric2 ]))
		;
	}

	function testAddTiming()
	{
		$this
			->given(
				$bucket = uniqid(),
				$value = rand(0, PHP_INT_MAX)
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->addTiming($bucket, $value))
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance([ new timing($bucket, $value) ]))
		;
	}

	function testAddGauge()
	{
		$this
			->given(
				$bucket = uniqid(),
				$value = rand(- PHP_INT_MAX, PHP_INT_MAX)
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->addGauge($bucket, $value))
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance([ new gauge($bucket, $value) ]))
		;
	}

	function testAddCounting()
	{
		$this
			->given(
				$bucket = uniqid(),
				$value = rand(- PHP_INT_MAX, PHP_INT_MAX)
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->addCounting($bucket, $value))
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance([ new counting($bucket, $value) ]))
		;
	}

	function testAddSet()
	{
		$this
			->given(
				$bucket = uniqid(),
				$value = rand(- PHP_INT_MAX, PHP_INT_MAX)
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->addSet($bucket, $value))
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance([ new set($bucket, $value) ]))
		;
	}
}
