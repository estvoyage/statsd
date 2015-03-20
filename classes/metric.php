<?php

namespace estvoyage\statsd;

use
	estvoyage\data
;

interface metric extends metric\provider
{
	function parentBucketIs(metric\bucket $bucket);
	function statsdMetricTemplateIs(metric\template $template);
}
