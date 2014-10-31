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

	function test__construct()
	{
		$this
			->exception(function() use (& $port) { $this->newTestedInstance($port = rand(- PHP_INT_MAX, -1)); })
				->isInstanceOf('estvoyage\statsd\port\exception')
				->hasMessage('\'' . $port . '\' is not a valid port')

			->exception(function() use (& $port) { $this->newTestedInstance($port = rand(65536, PHP_INT_MAX)); })
				->isInstanceOf('estvoyage\statsd\port\exception')
				->hasMessage('\'' . $port . '\' is not a valid port')

			->exception(function() use (& $port) { $this->newTestedInstance($port = ''); })
				->isInstanceOf('estvoyage\statsd\port\exception')
				->hasMessage('\'' . $port . '\' is not a valid port')

			->exception(function() use (& $port) { $this->newTestedInstance($port = 1.1); })
				->isInstanceOf('estvoyage\statsd\port\exception')
				->hasMessage('\'' . $port . '\' is not a valid port')
		;
	}

	function testOpenSocket()
	{
		$this
			->given(
				$socket = new statsd\socket,
				$host = uniqid()
			)
			->if(
				$this->calling($socket)->open = $openedSocket = new statsd\socket,
				$this->newTestedInstance($port = rand(0, 65535))
			)
			->then
				->object($this->testedInstance->openSocket($socket, $host))->isIdenticalTo($openedSocket)
				->mock($socket)->call('open')->withIdenticalArguments($host, $port)->once
		;
	}
}
