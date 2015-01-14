<?php

namespace estvoyage\statsd\metric\value\type;

use
	estvoyage\statsd\metric\value
;

class counting extends value\type
{
	static function build()
	{
		return parent::buildType('c');
	}
}
