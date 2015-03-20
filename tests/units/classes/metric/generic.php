<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\data,
	estvoyage\statsd\metric,
	mock\estvoyage\data as mockOfData,
	mock\estvoyage\statsd as mockOfStatsd
;

class generic extends units\test
{
	function beforeTestMethod($method)
	{
		require_once 'mock/statsd/metric/bucket.php';
		require_once 'mock/statsd/metric/value.php';
		require_once 'mock/statsd/metric/sampling.php';
	}

	function testClass()
	{
		$this->testedClass
			->isAbstract
			->implements('estvoyage\statsd\metric')
		;
	}

	function testParentBucketIs()
	{
		$this
			->given(
				$value = new metric\value(rand(- PHP_INT_MAX, PHP_INT_MAX)),
				$this->calling($bucket = new mockOfStatsd\metric\bucket)->parentBucketIs = $childBucket = new metric\bucket,
				$parentBucket = new metric\bucket(uniqid())
			)
			->if(
				$this->newTestedInstance($bucket, $value)
			)
			->then
				->object($this->testedInstance->parentBucketIs($parentBucket))
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance($childBucket, $value))
		;
	}
}
