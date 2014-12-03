<?php

namespace estvoyage\statsd\tests\units\value;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\value
;

class sampling extends units\test
{
	function testClass()
	{
		$this->testedClass
			->extends('estvoyage\value\float\unsigned')
		;
	}

	/**
	 * @dataProvider validValueProvider
	 */
	function testContructorWithValidValue($value)
	{
		$this->float($this->newTestedInstance($value)->asFloat)->isEqualTo($value);
	}

	/**
	 * @dataProvider validValueProvider
	 */
	function testValidateWithValidValue($value)
	{
		$this->boolean(value\sampling::validate($value))->isTrue;
	}

	/**
	 * @dataProvider invalidValueProvider
	 */
	function testContructorWithInvalidValue($value)
	{
		$this->exception(function() use ($value) { $this->newTestedInstance($value); })
			->isInstanceOf('domainException')
			->hasMessage('Sampling should be a float greater than 0.')
		;
	}

	/**
	 * @dataProvider invalidValueProvider
	 */
	function testValidateWithInvalidValue($value)
	{
		$this->boolean(value\sampling::validate($value))->isFalse;
	}

	/**
	 * @dataProvider validValueProvider
	 */
	function testCastToString($value)
	{
		$this->castToString($this->newTestedInstance($value))->isEqualTo((string) (float) $value);
	}

	function testImmutability()
	{
		$this
			->if(
				$this->newTestedInstance
			)
			->then
				->exception(function() { $this->testedInstance->asFloat = uniqid(); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($this->testedInstance) . ' is immutable')

				->exception(function() { $this->testedInstance->{uniqid()} = uniqid(); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($this->testedInstance) . ' is immutable')

				->exception(function() { unset($this->testedInstance->asFloat); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($this->testedInstance) . ' is immutable')

				->exception(function() { unset($this->testedInstance->{uniqid()}); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($this->testedInstance) . ' is immutable')
		;
	}

	protected function validValueProvider()
	{
		return [
			rand(1, PHP_INT_MAX),
			(float) rand(1, PHP_INT_MAX)
		];
	}

	protected function invalidValueProvider()
	{
		return [
			null,
			true,
			false,
			0,
			- rand(1, PHP_INT_MAX),
			- (float) rand(1, PHP_INT_MAX),
			0.,
			[ [] ],
			new \stdclass,
			''
		];
	}
}
