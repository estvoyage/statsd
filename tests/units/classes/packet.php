<?php

namespace seshat\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	seshat\statsd,
	mock\seshat\statsd\world\connection\socket
;

class packet extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('seshat\statsd\world\packet')
		;
	}

	function testWriteOnSocket()
	{
		$this
			->given(
				$socket = new socket
			)
			->if(
				$this->newTestedInstance($bucket = uniqid(), $value = uniqid(), $type = uniqid())
			)
			->then
				->object($this->testedInstance->writeOnSocket($socket, $host = uniqid(), $port = uniqid()))->isTestedInstance
				->mock($socket)->call('sendTo')->withIdenticalArguments($bucket . ':' . $value . '|' . $type, $host, $port, null)->once
			->if(
				$this->newTestedInstance($bucket = uniqid(), $value = uniqid(), $type = uniqid(), 1)
			)
			->then
				->object($this->testedInstance->writeOnSocket($socket, $host = uniqid(), $port = uniqid()))->isTestedInstance
				->mock($socket)->call('sendTo')->withIdenticalArguments($bucket . ':' . $value . '|' . $type, $host, $port, null)->once
			->if(
				$this->newTestedInstance($bucket = uniqid(), $value = uniqid(), $type = uniqid(), $sampleRate = uniqid())
			)
			->then
				->object($this->testedInstance->writeOnSocket($socket, $host = uniqid(), $port = uniqid()))->isTestedInstance
				->mock($socket)->call('sendTo')->withIdenticalArguments($bucket . ':' . $value . '|' . $type . '|@' . $sampleRate, $host, $port, null)->once

				->object($this->testedInstance->writeOnSocket($socket, $host = uniqid(), $port = uniqid(), $timeout = uniqid()))->isTestedInstance
				->mock($socket)->call('sendTo')->withIdenticalArguments($bucket . ':' . $value . '|' . $type . '|@' . $sampleRate, $host, $port, $timeout)->once
		;
	}
}
