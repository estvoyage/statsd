<?php

namespace estvoyage\statsd\value\type;

use
	estvoyage\statsd\value
;

class timing extends value\type
{
	static function build()
	{
		return parent::buildWith('ms');
	}
}
