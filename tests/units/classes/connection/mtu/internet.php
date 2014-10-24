<?php

namespace estvoyage\statsd\tests\units\connection\mtu;

require __DIR__ . '/../../../runner.php';

use
	mock\estvoyage\statsd\world as statsd
;

class internet extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\connection\mtu')
			->extends('estvoyage\statsd\connection\mtu')
		;
	}

	function testAdd()
	{
		$this
			->given(
				$callback = function($mtu) use (& $mtuAfterAdd) { $mtuAfterAdd = $mtu; }
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->add('a', $callback))->isTestedInstance
				->object($mtuAfterAdd)
					->isNotTestedInstance

				->exception(function() use (& $data) { $this->testedInstance->add($data = str_repeat('a', 513), function() {}); })
					->isInstanceOf('estvoyage\statsd\connection\mtu\exception')
					->hasMessage('\'' . $data . '\' exceed MTU size')
		;
	}
}
