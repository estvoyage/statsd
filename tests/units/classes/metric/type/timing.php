<?php

namespace estvoyage\statsd\tests\units\metric\type;

require __DIR__ . '/../../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric\type
;

class timing extends units\test
{
	function testBuild()
	{
		$this
			->object(type\timing::build())->isIdenticalTo(type\timing::build())
			->string(type\timing::build()->asString)->isEqualTo('ms')
		;
	}

	function testCastToString()
	{
		$this->castToString(type\timing::build())->isEqualTo('ms');
	}

	function testImmutability()
	{
		$this
			->if(
				$timing = type\timing::build()
			)
			->then
				->exception(function() use ($timing) { $timing->asString = uniqid(); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($timing) . ' is immutable')

				->exception(function() use ($timing) { $timing->{uniqid()} = uniqid(); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($timing) . ' is immutable')

				->exception(function() use ($timing) { unset($timing->asString); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($timing) . ' is immutable')

				->exception(function() use ($timing) { unset($timing->{uniqid()}); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($timing) . ' is immutable')
		;
	}
}
