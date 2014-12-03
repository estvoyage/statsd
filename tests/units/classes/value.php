<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd,
	estvoyage\statsd\value\type
;

class value extends test
{
	function testClass()
	{
		$this->testedClass
			->extends('estvoyage\value\integer')
		;
	}

	/**
	 * @dataProvider validValueProvider
	 */
	function testContructorWithValidValue($value, $type)
	{
		$this
			->integer($this->newTestedInstance($value, $type)->asInteger)->isIdenticalTo($value)
		;
	}

	/**
	 * @dataProvider validValueProvider
	 */
	function testCastToString($value, $type)
	{
		$this->castToString($this->newTestedInstance($value, $type))->isEqualTo($value . '|' . $type);
	}

	/**
	 * @dataProvider invalidValueProvider
	 */
	function testContructorWithInvalidValue($value)
	{
		$this->exception(function() use ($value) { $this->newTestedInstance($value, type\counting::build()); })
			->isInstanceOf('domainException')
			->hasMessage('Value should be an integer')
		;
	}

	/**
	 * @dataProvider validValueProvider
	 */
	function testValidateWithValidValue($value, $type)
	{
		$this->boolean(statsd\value::validate($value))->isTrue;
	}

	/**
	 * @dataProvider invalidValueProvider
	 */
	function testValidateWithInvalidValue($value)
	{
		$this->boolean(statsd\value::validate($value))->isFalse;
	}

	protected function validValueProvider()
	{
		return [
			[ - rand(1, PHP_INT_MAX), type\counting::build() ],
			[ 0, type\counting::build() ],
			[ rand(1, PHP_INT_MAX), type\counting::build() ]
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
