<?php

namespace estvoyage\statsd\tests\units\connection;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	mock\estvoyage\statsd\world as statsd
;

class intranet extends units\test
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\connection')
		;
	}

	function testWrite()
	{
		$this
			->given(
				$dataLessThanMtu = uniqid(),
				$dataGreaterThanMtu = str_repeat('a', 1433)
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
