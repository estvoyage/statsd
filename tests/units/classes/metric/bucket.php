<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric\bucket as testedClass
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
			->hasMessage('Bucket should be a string which contains alphanumeric characters or underscore')
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
			'any not empty string' => uniqid(),
			'numbers' => '12345.6789',
			'foo.bar' => uniqid(),
			'fOo.bAR' => uniqid(),
			'foo.bar._' => uniqid(),
			'foo_bar.bar' => uniqid()
		];
	}

	protected function invalidValueProvider()
	{
		return [
			'null' => null,
			'true' => true,
			'false' => false,
			'any integer' => rand(- PHP_INT_MAX, PHP_INT_MAX),
			'negative integer as string' => (string) rand(- PHP_INT_MAX, -1),
			'any float' => (float) rand(- PHP_INT_MAX, PHP_INT_MAX),
			'negative float as string' => (string) (float) rand(- PHP_INT_MAX, -1),
			'array' => [ [] ],
			'object' => new \stdclass,
			'empty string' => '',
			'End of line' => "\n",
			'Pipe' => '|',
			'Arobase' => '@',
			'Space' => ' ',
			'Tab' => "\t",
			'Dot' => '.',
			'string begin with dot' => '.foo',
			'string end with dot' => 'foo.',
			'string with -' => 'foo-bar.bar'
		];
	}
}
