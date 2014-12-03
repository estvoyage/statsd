<?php

namespace estvoyage\statsd\tests\units\value;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\value
;

class counting extends units\test
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
		$this
			->integer($this->newTestedInstance($value)->asInteger)->isIdenticalTo($value)
			->integer($this->newTestedInstance($value, new value\sampling)->asInteger)->isIdenticalTo($value)
		;
	}

	/**
	 * @dataProvider validValueProvider
	 */
	function testCastToString($value)
	{
		$this
			->if(
				$sampling = new value\sampling(rand(1, 10) / 100)
			)
			->then
				->castToString($this->newTestedInstance($value))->isEqualTo($value . '|c')
				->castToString($this->newTestedInstance($value, new value\sampling))->isEqualTo($value . '|c')
				->castToString($this->newTestedInstance($value, $sampling))->isEqualTo($value . '|c|@' . $sampling)
		;
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
