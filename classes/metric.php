<?php

namespace estvoyage\statsd;

abstract class metric extends \estvoyage\value\string
{
	function __construct(bucket $bucket, metric\value $value, metric\type $type, metric\value\sampling $sampling = null)
	{
		parent::__construct($bucket . ':' . $value . '|' . $type . (! $sampling || (string) $sampling == 1. ? '' : '|@' . $sampling));
	}
}
