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
				$dataLessThanMtu = uniqid(),
				$dataGreaterThanMtu = str_repeat('a', 513)
			)
			->if(
				$this->newTestedInstance(new statsd\address)
			)
			->then
				->object($this->testedInstance->write($dataLessThanMtu))
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)

				->exception(function() use ($dataGreaterThanMtu) { $this->testedInstance->write($dataGreaterThanMtu); })
					->isInstanceOf('estvoyage\statsd\connection\exception')
					->hasMessage('MTU size exceeded')
		;
	}
}
