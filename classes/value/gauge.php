<?php

namespace estvoyage\statsd\value;

use
	estvoyage\statsd,
	estvoyage\statsd\world\value
;

class gauge extends statsd\value
{
	function __construct($value, value\sampling $sampling = null)
	{
		if (filter_var($value, FILTER_VALIDATE_FLOAT) === false)
		{
			throw new timing\exception('Gauge must be a number');
		}

		parent::__construct($value, 'g', $sampling);
	}
}

