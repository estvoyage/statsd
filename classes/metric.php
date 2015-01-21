<?php

namespace estvoyage\statsd;

abstract class metric extends \estvoyage\value\string
{
	function __construct(metric\bucket $bucket, metric\value $value, metric\type $type, metric\sampling $sampling = null)
	{
		parent::__construct($bucket . ':' . $value . '|' . $type . (! $sampling || (string) $sampling == 1. ? '' : '|@' . $sampling));
	}
}
