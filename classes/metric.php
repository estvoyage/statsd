<?php

namespace estvoyage\statsd;

use
	estvoyage\net\socket
;

abstract class metric extends \estvoyage\value\string
{
	function __construct(bucket $bucket, value $value)
	{
		parent::__construct($bucket . ':' . $value);
	}
}
