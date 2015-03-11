<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\net,
	estvoyage\data,
	mock\estvoyage\data as mockOfData
;

class consumer extends units\test
{
	function beforeTestMethod($method)
	{
		require_once 'mock/net/mtu.php';
	}

	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\data\consumer')
		;
	}

	function testDataProviderIs()
	{
		$this
			->given(
				$mtu = new net\mtu(rand(0, PHP_INT_MAX)),
				$dataConsumer = new mockOfData\consumer,
				$dataProvider = new mockOfData\provider
			)
			->if(
				$this->newTestedInstance($dataConsumer, $mtu)
			)
			->then
				->object($this->testedInstance->dataProviderIs($dataProvider))->isTestedInstance
				->mock($dataProvider)
					->receive('dataConsumerIs')
						->withArguments($this->testedInstance)
							->once
		;
	}

	function testNewData()
	{
		$this
			->given(
				$mtu = new net\mtu(2),
				$dataConsumer = new mockOfData\consumer
			)

			->if(
				$this->newTestedInstance($dataConsumer, $mtu)
			)
			->then
				->object($this->testedInstance->newData(new data\data('a')))->isTestedInstance
				->mock($dataConsumer)
					->receive('newData')
						->never

			->if(
				$this->testedInstance->newData(new data\data('bb'))
			)
			->then
				->mock($dataConsumer)
					->receive('newData')
						->withArguments('a')
							->once
						->withArguments('bb')
							->never

			->exception(function() { $this->testedInstance->newData(new data\data('ccc')); })
				->isInstanceOf('estvoyage\net\mtu\exception\overflow')
				->hasMessage('Length of data \'ccc\' exceed MTU 2')
		;
	}

	function testNoMoreData()
	{
		$this
			->given(
				$mtu = new net\mtu(2),
				$dataConsumer = new mockOfData\consumer
			)

			->if(
				$this->newTestedInstance($dataConsumer, $mtu)
			)
			->then
				->object($this->testedInstance->noMoreData())->isTestedInstance
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data)
							->once

			->if(
				$data = new data\data('a'),
				$this->testedInstance->newData($data)->noMoreData()
			)
			->then
				->mock($dataConsumer)
					->receive('newData')
						->withArguments($data)
							->once
		;
	}
}