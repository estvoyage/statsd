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
				$socket = new statsd\socket
			)

			->if(
				$this->calling($host)->openSocket = $openedSocket = new statsd\socket,
				$this->newTestedInstance($host, $port)
			)
			->then
				->object($this->testedInstance->openSocket($socket))->isIdenticalTo($openedSocket)
				->mock($host)->call('openSocket')->withIdenticalArguments($socket, $port)->once

			->if(
				$this->calling($socket)->open = $openedSocket,
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->openSocket($socket))->isIdenticalTo($openedSocket)
				->mock($socket)->call('open')->withIdenticalArguments('127.0.0.1', 8125)->once
		;
	}
}
