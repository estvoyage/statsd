<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	mock\estvoyage\statsd\world as statsd
;

class port extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\port')
		;
	}

	function testOpenSocket()
	{
		$this
			->given(
				$socket = new statsd\socket,
				$host = uniqid(),
				$callback = function() {}
			)
			->if(
				$this->newTestedInstance($port = rand(0, 65535))
			)
			->then
				->object($this->testedInstance->openSocket($socket, $host, $callback))->isTestedInstance
				->mock($socket)->call('open')->withIdenticalArguments($host, $port, $callback)->once
		;
	}
}
