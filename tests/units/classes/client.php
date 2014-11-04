<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	mock\estvoyage\statsd\world as statsd
;

class client extends \atoum
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
