<?php

namespace estvoyage\statsd\metric\value\type;

use
	estvoyage\value\string,
	estvoyage\statsd\metric\value
;

class timing extends value\type
{
	static function build()
	{
		return parent::buildType('ms');
	}
}
