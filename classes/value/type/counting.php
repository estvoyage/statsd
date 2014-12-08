<?php

namespace estvoyage\statsd\value\type;

use
	estvoyage\statsd\value
;

class counting extends value\type
{
	static function build()
	{
		return parent::buildType('c');
	}
}
