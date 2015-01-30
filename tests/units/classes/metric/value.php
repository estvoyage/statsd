<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric
;

class value extends units\test
{
	function testClass()
	{
		$this->testedClass
			->extends('estvoyage\value\string')
		;
	}

	/**
	  * @dataProvider constructorWithValidProvider
	  */
	function testConstructorWithValid($value, $type, $sampling, $metricWithoutSampling, $metricWithSampling)
	{
		$this
			->castToString(new metric\value($value, $type))->isEqualTo($metricWithoutSampling)
			->castToString(new metric\value($value, $type, $sampling))->isEqualTo($metricWithSampling)
		;
	}

	/**
	  * @dataProvider constructorWithInvalidValueProvider
	  */
	function testConstructorWithInvalidValue($invalidValue)
	{
		$this
			->exception(function() use ($invalidValue) {
						new metric\value($invalidValue, uniqid());
					}
				)
				->isInstanceOf('domainException')
				->hasMessage('Value should be an integer')
		;
	}

	/**
	  * @dataProvider constructorWithInvalidTypeProvider
	  */
	function testConstructorWithInvalidType($invalidType)
	{
		$this
			->exception(function() use ($invalidType) {
						new metric\value(rand(- PHP_INT_MAX, PHP_INT_MAX), $invalidType);
					}
				)
				->isInstanceOf('domainException')
				->hasMessage('Type should be a not empty string')
		;
	}

	/**
	  * @dataProvider constructorWithInvalidSamplingProvider
	  */
	function testConstructorWithInvalidSampling($invalidSampling)
	{
		$this
			->exception(function() use ($invalidSampling) {
						new metric\value(rand(- PHP_INT_MAX, PHP_INT_MAX), uniqid(), $invalidSampling);
					}
				)
				->isInstanceOf('domainException')
				->hasMessage('Sampling should be a float greater than 0.')
		;
	}

	function testGauge()
	{
		$this
			->given(
				$value = rand(- PHP_INT_MAX, PHP_INT_MAX)
			)

			->if(
				$gauge = metric\value::gauge($value)
			)
			->then
				->object($gauge)->isEqualTo(new metric\value($value, 'g'))
		;
	}

	function testCounting()
	{
		$this
			->given(
				$value = rand(- PHP_INT_MAX, PHP_INT_MAX),
				$sampling = rand(1, 100) / 1000
			)

			->if(
				$gauge = metric\value::counting()
			)
			->then
				->object($gauge)->isEqualTo(new metric\value(1, 'c'))

			->if(
				$gauge = metric\value::counting($value)
			)
			->then
				->object($gauge)->isEqualTo(new metric\value($value, 'c'))

			->if(
				$gauge = metric\value::counting($value, $sampling)
			)
			->then
				->object($gauge)->isEqualTo(new metric\value($value, 'c', $sampling))
		;
	}

	function testSet()
	{
		$this
			->given(
				$value = rand(- PHP_INT_MAX, PHP_INT_MAX)
			)

			->if(
				$set = metric\value::set($value)
			)
			->then
				->object($set)->isEqualTo(new metric\value($value, 's'))
		;
	}

	function testTiming()
	{
		$this
			->given(
				$value = rand(- PHP_INT_MAX, PHP_INT_MAX)
			)

			->if(
				$timing = metric\value::timing($value)
			)
			->then
				->object($timing)->isEqualTo(new metric\value($value, 'ms'))
		;
	}

	protected function constructorWithValidProvider()
	{
		return [
			'sampling is equal to 1.0' => [
				$value = rand(- PHP_INT_MAX, PHP_INT_MAX),
				$type = uniqid(),
				1.0,
				$metric = $value . '|' . $type . PHP_EOL,
				$metric
			],
			'sampling is not equal to 1.0' => [
				$value = rand(- PHP_INT_MAX, PHP_INT_MAX),
				$type = uniqid(),
				$sampling = rand(1, 100) / 1000,
				($metric = $value . '|' . $type) . PHP_EOL,
				$metric .= '|@' . $sampling . PHP_EOL
			],
			'Value is an integer as string' => [
				$value = (string) rand(- PHP_INT_MAX, PHP_INT_MAX),
				$type = uniqid(),
				$sampling = rand(1, 100) / 1000,
				($metric = $value . '|' . $type) . PHP_EOL,
				$metric .= '|@' . $sampling . PHP_EOL
			]
		];
	}

	protected function constructorWithInvalidValueProvider()
	{
		return [
			'a string' => 'foo',
			'an empty string' => '',
			'a float' => rand(1, 100) / 1000,
			'an array' => [ [] ],
			'null' => null,
			'a boolean' => rand(0, 1) == 1,
			'an object' => new \stdclass
		];
	}

	protected function constructorWithInvalidTypeProvider()
	{
		return [
			'an empty string' => '',
			'an integer' => rand(- PHP_INT_MAX, PHP_INT_MAX),
			'a float' => rand(1, 100) / 1000,
			'an array' => [ [] ],
			'null' => null,
			'a boolean' => rand(0, 1) == 1,
			'an object' => new \stdclass
		];
	}

	protected function constructorWithInvalidSamplingProvider()
	{
		return [
			'an empty string' => '',
			'a negative float' => - rand(1, 100) / 1000,
			'zero as float' => 0.,
			'an array' => [ [] ],
			'null' => null,
			'a boolean' => rand(0, 1) == 1,
			'an object' => new \stdclass
		];
	}
}
