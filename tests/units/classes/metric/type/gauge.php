<?php

namespace estvoyage\statsd\tests\units\metric\type;

require __DIR__ . '/../../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric\type
;

class gauge extends units\test
{
	function testBuild()
	{
		$this
			->object(type\gauge::build())->isIdenticalTo(type\gauge::build())
			->string(type\gauge::build()->asString)->isEqualTo('g')
		;
	}

	function testCastToString()
	{
		$this->castToString(type\gauge::build())->isEqualTo('g');
	}

	function testImmutability()
	{
		$this
			->if(
				$gauge = type\gauge::build()
			)
			->then
				->exception(function() use ($gauge) { $gauge->asString = uniqid(); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($gauge) . ' is immutable')

				->exception(function() use ($gauge) { $gauge->{uniqid()} = uniqid(); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($gauge) . ' is immutable')

				->exception(function() use ($gauge) { unset($gauge->asString); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($gauge) . ' is immutable')

				->exception(function() use ($gauge) { unset($gauge->{uniqid()}); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($gauge) . ' is immutable')
		;
	}
}
