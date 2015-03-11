<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\statsd,
	estvoyage\statsd\metric
;

final class timing extends generic
{
	function statsdMetricTemplateIs(template $template)
	{
		return $this->isTimingAndStatsdMetricTemplateIs($template);
	}
}
