<?php

namespace estvoyage\statsd;

use
	estvoyage\net\socket
;

abstract class metric extends \estvoyage\value\string
{
	function __construct(bucket $bucket, value $value, value\type $type, value\sampling $sampling = null)
	{
		parent::__construct($bucket . ':' . $value . '|' . $type . (! $sampling || (string) $sampling == 1. ? '' : '|@' . $sampling));
	}
}
