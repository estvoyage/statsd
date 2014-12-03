<?php

namespace estvoyage\statsd\tests\units\value;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\value
;

class set extends units\test
{
	function testClass()
	{
		$this->testedClass
			->extends('estvoyage\statsd\value')
		;
	}

	/**
	 * @dataProvider validValueProvider
	 */
	function testContructorWithValidValue($value)
	{
		$this->integer($this->newTestedInstance($value)->asInteger)->isIdenticalTo($value);
	}

	/**
	 * @dataProvider validValueProvider
	 */
	function testCastToString($value)
	{
		$this->castToString($this->newTestedInstance($value))->isEqualTo($value . '|s');
	}

	protected function validValueProvider()
	{
		return [
			- rand(1, PHP_INT_MAX),
			0,
			rand(1, PHP_INT_MAX)
		];
	}

	protected function invalidValueProvider()
	{
		return [
			null,
			true,
			false,
			(float) rand(- PHP_INT_MAX, PHP_INT_MAX),
			[ [] ],
			new \stdclass,
			'',
			uniqid()
		];
	}
}
