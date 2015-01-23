<?php

namespace estvoyage\statsd\metric;

class bucket extends \estvoyage\value\string
{
	function __construct($value = '')
	{
		parent::__construct($value);
	}
}
