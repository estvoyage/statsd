<?php

namespace estvoyage\statsd\value;

use
	estvoyage\statsd,
	estvoyage\statsd\world\value
;

class counting extends statsd\value
{
	function __construct($value, value\sampling $sampling = null)
	{
		if (filter_var($value, FILTER_VALIDATE_INT) === false)
		{
			throw new timing\exception('Counting must be an integer');
		}

		parent::__construct($value, 'c', $sampling);
	}
}
