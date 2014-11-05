<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd\tests\units,
	mock\estvoyage\statsd\world as statsd
;

class client extends units\test
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\client')
		;
	}

	function testSend()
	{
		$this
			->given(
				$connection = new statsd\connection,
				$data = new statsd\connection\data
			)
			->if(
				$this->newTestedInstance($connection)
			)
			->then
				->object($this->testedInstance->send($data))->isTestedInstance
				->mock($data)->call('writeOn')->withIdenticalArguments($connection)->once
		;
	}
}
