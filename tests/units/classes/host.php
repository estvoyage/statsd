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

	function test__construct()
	{
		$this
			->exception(function() { $this->newTestedInstance(''); })
				->isInstanceOf('estvoyage\statsd\host\exception')
				->hasMessage('\'\' is not a valid host')

			->exception(function() { $this->newTestedInstance('-'); })
				->isInstanceOf('estvoyage\statsd\host\exception')
				->hasMessage('\'-\' is not a valid host')

			->exception(function() { $this->newTestedInstance('a b'); })
				->isInstanceOf('estvoyage\statsd\host\exception')
				->hasMessage('\'a b\' is not a valid host')

			->exception(function() { $this->newTestedInstance('a,b'); })
				->isInstanceOf('estvoyage\statsd\host\exception')
				->hasMessage('\'a,b\' is not a valid host')

			->exception(function() use (& $host) { $this->newTestedInstance($host = str_repeat('a', 64)); })
				->isInstanceOf('estvoyage\statsd\host\exception')
				->hasMessage('\'' . $host . '\' is not a valid host')

			->exception(function() use (& $host) { $this->newTestedInstance($host = str_repeat('a', 63) . '.' . str_repeat('b', 63) . '.' . str_repeat('c', 63) . '.' . str_repeat('d', 64)); })
				->isInstanceOf('estvoyage\statsd\host\exception')
				->hasMessage('\'' . $host . '\' is not a valid host')
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
				$this->newTestedInstance('foo.bar')
			)
			->then
				->object($this->testedInstance->openSocket($socket, $port, $callback))->isTestedInstance
				->mock($port)->call('openSocket')->withIdenticalArguments($socket, 'foo.bar', $callback)->once
		;
	}
}
