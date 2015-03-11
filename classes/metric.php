<?php

namespace estvoyage\statsd;

use
	estvoyage\data
;

interface metric
{
	function statsdMetricTemplateIs(metric\template $template);
}
