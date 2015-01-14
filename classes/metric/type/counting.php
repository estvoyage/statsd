<?php

namespace estvoyage\statsd\metric\type;

use
	estvoyage\statsd\metric
;

class counting extends metric\type
{
	static function build()
	{
		return parent::buildType('c');
	}
}
