<?php

namespace estvoyage\statsd;

use
	estvoyage\net\socket
;

class metric
{
	use \estvoyage\value\immutable;

	function __construct(bucket $bucket, value $value)
	{
		$this->values['data'] = new socket\data($bucket . ':' . $value);
	}

	function __toString()
	{
		return (string) $this->data;
	}
}
