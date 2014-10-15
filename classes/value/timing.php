<?php

namespace estvoyage\statsd\value;

use
	estvoyage\statsd,
	estvoyage\statsd\world\value
;

class timing extends statsd\value
{
	function __construct($value, value\sampling $sampling = null)
	{
		if (filter_var($value, FILTER_VALIDATE_INT) === false || $value < 0)
		{
			throw new timing\exception('Timing must be an integer greater than or equal to 0');
		}

		parent::__construct($value, 't', $sampling);
	}
}
