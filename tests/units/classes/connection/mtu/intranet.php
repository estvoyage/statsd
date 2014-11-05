<?php

namespace estvoyage\statsd\tests\units\connection\mtu;

require __DIR__ . '/../../../runner.php';

use
	estvoyage\statsd\tests\units,
	mock\estvoyage\statsd\world as statsd
;

class intranet extends units\test
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\connection\mtu')
		;
	}

	function testAdd()
	{
		// See https://github.com/etsy/statsd/blob/master/docs/metric_types.md

		$this
			->given(
				$dataLessThanMtu = uniqid(),
				$dataGreaterThanMtu = str_repeat('a', 1433)
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->add($dataLessThanMtu))
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)

				->exception(function() use ($dataGreaterThanMtu) { $this->testedInstance->add($dataGreaterThanMtu); })
					->isInstanceOf('estvoyage\statsd\connection\mtu\exception')
					->hasMessage('\'' . $dataGreaterThanMtu . '\' exceed MTU size')
		;
	}
}
