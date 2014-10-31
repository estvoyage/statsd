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
				$this->calling($port = new statsd\port)->openSocket = $openedSocket = new statsd\Socket
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->openSocket($socket, $port))->isIdenticalTo($openedSocket)
				->mock($port)->call('openSocket')->withIdenticalArguments($socket, '127.0.0.1')->once
		;
	}
}
