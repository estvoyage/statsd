<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd,
	estvoyage\statsd\value\type,
	estvoyage\statsd\tests\units
;

class value extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->extends('estvoyage\value\integer')
		;
	}

	/**
	 * @dataProvider validValueProvider
	 */
	function testContructorWithValidValue($value)
	{
		$this
			->integer($this->newTestedInstance($value)->asInteger)->isEqualTo($value)
		;
	}

	/**
	 * @dataProvider validValueProvider
	 */
	function testCastToString($value)
	{
		$this
			->castToString($this->newTestedInstance($value))->isEqualTo($value)
		;
	}

	/**
	 * @dataProvider invalidValueProvider
	 */
	function testContructorWithInvalidValue($value)
	{
		$this->exception(function() use ($value) { $this->newTestedInstance($value); })
			->isInstanceOf('domainException')
			->hasMessage('Value should be an integer')
		;
	}

	/**
	 * @dataProvider validValueProvider
	 */
	function testValidateWithValidValue($value)
	{
		$this->boolean(statsd\metric\value::validate($value))->isTrue;
	}

	/**
	 * @dataProvider invalidValueProvider
	 */
	function testValidateWithInvalidValue($value)
	{
		$this->boolean(statsd\metric\value::validate($value))->isFalse;
	}

	protected function validValueProvider()
	{
		return [
			'negative integer' => - rand(1, PHP_INT_MAX),
			'zero as integer' => 0,
			'positive integer' => rand(1, PHP_INT_MAX)
		];
	}

	protected function invalidValueProvider()
	{
		return [
			'null' => null,
			'true' => true,
			'false' => false,
			'array' => [ [] ],
			'object' => new \stdclass,
			'empty string' => '',
			'any string' => uniqid() . ' ' . uniqid()
		];
	}
}
