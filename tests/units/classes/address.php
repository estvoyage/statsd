<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	mock\estvoyage\statsd\world as statsd
;

class address extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\address')
		;
	}

	function testOpenSocket()
	{
		$this
			->given(
				$host = new statsd\host,
				$port = new statsd\port,
				$socket = new statsd\socket,
				$callback = function() {}
			)

			->if(
				$this->newTestedInstance($host, $port)
			)
			->then
				->object($this->testedInstance->openSocket($socket, $callback))->isTestedInstance
				->mock($host)->call('openSocket')->withIdenticalArguments($socket, $port, $callback)->once

			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->openSocket($socket, $callback))->isTestedInstance
				->mock($socket)->call('open')->withIdenticalArguments('127.0.0.1', 8125, $callback)->once
		;
	}
}
