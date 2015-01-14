<?php

namespace estvoyage\statsd\tests\units\metric\value\type;

require __DIR__ . '/../../../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric\value\type
;

class set extends units\test
{
	function testBuild()
	{
		$this
			->object(type\set::build())->isIdenticalTo(type\set::build())
			->string(type\set::build()->asString)->isEqualTo('s')
		;
	}

	function testCastToString()
	{
		$this->castToString(type\set::build())->isEqualTo('s');
	}

	function testImmutability()
	{
		$this
			->if(
				$set = type\set::build()
			)
			->then
				->exception(function() use ($set) { $set->asString = uniqid(); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($set) . ' is immutable')

				->exception(function() use ($set) { $set->{uniqid()} = uniqid(); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($set) . ' is immutable')

				->exception(function() use ($set) { unset($set->asString); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($set) . ' is immutable')

				->exception(function() use ($set) { unset($set->{uniqid()}); })
					->isInstanceOf('logicException')
					->hasMessage(get_class($set) . ' is immutable')
		;
	}
}
