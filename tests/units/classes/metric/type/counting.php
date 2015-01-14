<?php

namespace estvoyage\statsd\tests\units\metric\type;

require __DIR__ . '/../../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric\type
;

class counting extends units\test
{
	function testBuild()
	{
		$this
			->object(type\counting::build())->isIdenticalTo(type\counting::build())
			->string(type\counting::build()->asString)->isEqualTo('c')
		;
	}

	function testCastToString()
	{
		$this->castToString(type\counting::build())->isEqualTo('c');
	}

	function testImmutability()
	{
		$this
			->if(
				$counting = type\counting::build()
			)
			->then
				->exception(function() use ($counting) { $counting->asString = uniqid(); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($counting) . ' is immutable')

				->exception(function() use ($counting) { $counting->{uniqid()} = uniqid(); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($counting) . ' is immutable')

				->exception(function() use ($counting) { unset($counting->asString); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($counting) . ' is immutable')

				->exception(function() use ($counting) { unset($counting->{uniqid()}); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($counting) . ' is immutable')
		;
	}
}
