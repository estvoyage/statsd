<?php

namespace estvoyage\statsd\value\type;

use
	estvoyage\value\string,
	estvoyage\statsd\value
;

class timing extends value\type
{
	static function build()
	{
		return parent::buildType('ms');
	}
}
