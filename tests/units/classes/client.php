<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	mock\estvoyage\statsd\world as statsd
;

class client extends \atoum
{
	function testSend()
	{
		$this
			->given(
				$connection = new statsd\connection,
				$packet = new statsd\packet
			)
			->if(
				$this->newTestedInstance($connection)
			)
			->then
				->object($this->testedInstance->send($packet))->isTestedInstance
				->mock($packet)->call('writeOn')->withIdenticalArguments($connection)->once
		;
	}
}
