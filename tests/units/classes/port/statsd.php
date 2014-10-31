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
				$this->calling($socket = new socket)->open = $openedSocket = new socket,
				$host = uniqid()
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->openSocket($socket, $host))->isIdenticalTo($openedSocket)
				->mock($socket)->call('open')->withIdenticalArguments($host, 8125)->once
		;
	}
}
