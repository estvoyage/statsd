<?php

namespace estvoyage\statsd\value;

use
	estvoyage\statsd,
	estvoyage\statsd\world\value
;

class set extends statsd\value
{
	function __construct($value)
	{
		if (filter_var($value, FILTER_VALIDATE_INT) === false)
		{
			throw new timing\exception('Set must be a number');
		}

		parent::__construct($value, 's');
	}
}
