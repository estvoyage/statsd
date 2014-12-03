<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\bucket as testedClass
;

class bucket extends units\test
{
	function testClass()
	{
		$this->testedClass
			->extends('estvoyage\value\string')
		;
	}

	/**
	 * @dataProvider validValueProvider
	 */
	function testContructorWithValidValue($value)
	{
		$this->string($this->newTestedInstance($value)->asString)->isIdenticalTo($value);
	}

	/**
	 * @dataProvider invalidValueProvider
	 */
	function testContructorWithInvalidValue($value)
	{
		$this->exception(function() use ($value) { $this->newTestedInstance($value); })
			->isInstanceOf('domainException')
			->hasMessage('Bucket should be a not empty string')
		;
	}

	/**
	 * @dataProvider validValueProvider
	 */
	function testValidateWithValidValue($value)
	{
		$this->boolean(testedClass::validate($value))->isTrue;
	}

	/**
	 * @dataProvider invalidValueProvider
	 */
	function testValidateWithInvalidValue($value)
	{
		$this->boolean(testedClass::validate($value))->isFalse;
	}

	protected function validValueProvider()
	{
		return [
			(string) rand(- PHP_INT_MAX, PHP_INT_MAX),
			(string) (float) rand(- PHP_INT_MAX, PHP_INT_MAX),
			uniqid()
		];
	}

	protected function invalidValueProvider()
	{
		return [
			null,
			true,
			false,
			rand(- PHP_INT_MAX, PHP_INT_MAX),
			(float) rand(- PHP_INT_MAX, PHP_INT_MAX),
			[ [] ],
			new \stdclass,
			'',
			"\n",
			'|',
			'@',
			' ',
			"\t"
		];
	}
}
