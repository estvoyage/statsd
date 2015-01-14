<?php

namespace estvoyage\statsd\metric\type;

use
	estvoyage\statsd\metric
;

class gauge extends metric\type
{
	static function build()
	{
		return parent::buildType('g');
	}
}
