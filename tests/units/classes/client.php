<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd\tests\units,
	mock\estvoyage\statsd\world as statsd
;

class client extends test
{
	function testSend()
	{
		$this
			->given(
				$packet = new statsd\packet,
				$connection = new statsd\connection
			)
			->if(
				$this->newTestedInstance($connection)
			)
			->then
				->object($this->testedInstance->send($packet))->isTestedInstance
				->mock($connection)->call('send')->withIdenticalArguments($packet)->once
		;
	}
}
