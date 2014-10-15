<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	mock\estvoyage\statsd\world\connection\socket
;

class connection extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\connection')
		;
	}

	function testSend()
	{
		$this
			->given(
				$bucket = uniqid(),
				$value = uniqid(),
				$type = uniqid(),
				$sampleRate = uniqid(),
				$host = uniqid(),
				$port = uniqid(),
				$timeout = uniqid(),
				$socket = new socket
			)
			->if(
				$this->newTestedInstance($host, $port, $socket)
			)
			->then
				->object($this->testedInstance->send($data = uniqid()))->isTestedInstance
				->mock($socket)->call('send')->withArguments($data, $host, $port, null)->once

				->object($this->testedInstance->send($data = uniqid(), $timeout))->isTestedInstance
				->mock($socket)->call('send')->withArguments($data, $host, $port, $timeout)->once
		;
	}
}
