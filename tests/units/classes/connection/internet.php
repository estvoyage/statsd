<?php

namespace estvoyage\statsd\tests\units\connection;

require __DIR__ . '/../../runner.php';

use
	mock\estvoyage\statsd\world as statsd
;

class internet extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\connection')
			->extends('estvoyage\statsd\connection')
		;
	}

	function testWrite()
	{
		$this
			->given(
				$callback = function($connection) use (& $connectionAfterWrite) { $connectionAfterWrite = $connection; }
			)
			->if(
				$this->newTestedInstance(new statsd\address)
			)
			->then
				->object($this->testedInstance->write(uniqid(), $callback))->isTestedInstance
				->object($connectionAfterWrite)
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)

				->exception(function() { $this->testedInstance->write(str_repeat('a', 513), function() {}); })
					->isInstanceOf('estvoyage\statsd\connection\exception')
					->hasMessage('MTU size exceeded')
		;
	}
}
