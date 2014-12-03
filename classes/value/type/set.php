<?php

namespace estvoyage\statsd\value\type;

use
	estvoyage\statsd\value
;

class set extends value\type
{
	static function build()
	{
		return parent::buildWith('s');
	}
}
