<?php

namespace estvoyage\statsd\value\type;

use
	estvoyage\statsd\value
;

class gauge extends value\type
{
	static function build()
	{
		return parent::buildWith('g');
	}
}
