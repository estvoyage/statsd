<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric
;

class bucket extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
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
			->hasMessage('Bucket name should be a string which contains alphanumeric characters, -, +, _, {, }, [, ], %')
		;
	}

	/**
	 * @dataProvider validValueProvider
	 */
	function testValidateWithValidValue($value)
	{
		$this->boolean(metric\bucket::validate($value))->isTrue;
	}

	/**
	 * @dataProvider invalidValueProvider
	 */
	function testValidateWithInvalidValue($value)
	{
		$this->boolean(metric\bucket::validate($value))->isFalse;
	}

	protected function validValueProvider()
	{
		return [
			'any not empty string' => uniqid(),
			'numbers' => '12345.6789',
			'negative integer as string' => (string) rand(- PHP_INT_MAX, -1),
			'negative float as string' => (string) (float) rand(- PHP_INT_MAX, -1),
			'foo.bar' => 'foo.bar',
			'fOo.bAR' => 'fOo.bAR',
			'foo.bar._' => 'foo.bar._',
			'foo.bar.%' => 'foo.bar.%',
			'{foo}.bar.%' => '{foo}.bar.%',
			':foo:.bar.%' => ':foo:.bar.%',
			'foo_bar.bar' => 'foo_bar.bar',
			'string with -' => 'foo-bar.bar'
		];
	}

	protected function invalidValueProvider()
	{
		return [
			'null' => null,
			'true' => true,
			'false' => false,
			'any integer' => rand(- PHP_INT_MAX, PHP_INT_MAX),
			'any float' => (float) rand(- PHP_INT_MAX, PHP_INT_MAX),
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
		];
	}
}
