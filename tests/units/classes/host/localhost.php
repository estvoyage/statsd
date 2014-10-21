<?php

namespace estvoyage\statsd\tests\units\host;

require __DIR__ . '/../../runner.php';

use
	mock\estvoyage\statsd\world as statsd
;

class localhost extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\host')
			->extends('estvoyage\statsd\host')
		;
	}

	function testOpenSocket()
	{
		$this
			->given(
				$socket = new statsd\socket,
				$port = new statsd\port,
				$callback = function() {}
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->openSocket($socket, $port, $callback))->isTestedInstance
				->mock($port)->call('openSocket')->withIdenticalArguments($socket, '127.0.0.1', $callback)->once
		;
	}
}
