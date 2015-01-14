<?php

namespace estvoyage\statsd\metric\type;

use
	estvoyage\statsd\metric
;

class set extends metric\type
{
	static function build()
	{
		return parent::buildType('s');
	}
}
