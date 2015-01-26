<?php

namespace estvoyage\statsd\metric;

class bucket
{
	use \estvoyage\value\world\string {
		__construct as private;
	}

	static function ofName($value)
	{
		return new self($value);
	}
}
