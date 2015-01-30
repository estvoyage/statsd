<?php

namespace estvoyage\statsd;

final class metric extends \estvoyage\value\string
{
	function __construct(metric\bucket $bucket, metric\value $value)
	{
		parent::__construct($bucket . ':' . $value);
	}
}
