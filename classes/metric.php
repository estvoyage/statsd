<?php

namespace estvoyage\statsd;

use
	estvoyage\data
;

interface metric extends metric\provider
{
	function statsdMetricTemplateIs(metric\template $template);
}
