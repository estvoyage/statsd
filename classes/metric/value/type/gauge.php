<?php

namespace estvoyage\statsd\metric\value\type;

use
	estvoyage\statsd\metric\value
;

class gauge extends value\type
{
	static function build()
	{
		return parent::buildType('g');
	}
}
