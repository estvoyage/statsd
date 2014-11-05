<?php

namespace estvoyage\statsd\tests\units\value;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	mock\estvoyage\statsd\world as statsd
;

class sampling extends units\test
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\value\sampling')
		;
	}

	function test__construct()
	{
		$this
			->exception(function() { $this->newTestedInstance(-0.1); })
				->isInstanceOf('estvoyage\statsd\value\sampling\exception')
				->hasMessage('Sampling must be a float greater than 0.0')
		;
	}

	function testWriteOn()
	{
		$this
			->given(
				$this->calling($connection = new statsd\connection)->write = $connectionWrited = new statsd\connection
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->writeOn($connection))->isIdenticalTo($connectionWrited)
				->mock($connection)->call('write')->withIdenticalArguments('')->once

			->if(
				$this->newTestedInstance(1.1)
			)
			->then
				->object($this->testedInstance->writeOn($connection))->isIdenticalTo($connectionWrited)
				->mock($connection)->call('write')->withIdenticalArguments('|@1.1')->once

			->if(
				$this->newTestedInstance(0.9)
			)
			->then
				->object($this->testedInstance->writeOn($connection))->isIdenticalTo($connectionWrited)
				->mock($connection)->call('write')->withIdenticalArguments('|@0.9')->once
		;
	}
}
