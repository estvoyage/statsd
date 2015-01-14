<?php

namespace estvoyage\statsd\metric\type;

use
	estvoyage\value\string,
	estvoyage\statsd\metric
;

class timing extends metric\type
{
	static function build()
	{
		return parent::buildType('ms');
	}
}
