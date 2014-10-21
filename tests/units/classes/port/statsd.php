<?php

namespace estvoyage\statsd\tests\units\port;

require __DIR__ . '/../../runner.php';

use
	mock\estvoyage\statsd\world\socket
;

class statsd extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\port')
			->extends('estvoyage\statsd\port')
		;
	}

	function testOpenSocket()
	{
		$this
			->given(
				$socket = new socket,
				$host = uniqid(),
				$callback = function() {}
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->openSocket($socket, $host, $callback))->isTestedInstance
				->mock($socket)->call('open')->withIdenticalArguments($host, 8125, $callback)->once
		;
	}
}
