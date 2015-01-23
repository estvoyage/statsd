<?php

namespace estvoyage\statsd\metric;

class value extends \estvoyage\value\integer
{
	function __construct($value = 0)
	{
		parent::__construct($value);
	}
}
