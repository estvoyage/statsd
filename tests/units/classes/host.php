<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	mock\estvoyage\statsd\world as statsd
;

class host extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\host')
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
				$this->newTestedInstance('http://foo.bar')
			)
			->then
				->object($this->testedInstance->openSocket($socket, $port, $callback))->isTestedInstance
				->mock($port)->call('openSocket')->withIdenticalArguments($socket, 'http://foo.bar', $callback)->once
		;
	}
}
