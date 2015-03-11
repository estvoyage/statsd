<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\statsd,
	estvoyage\statsd\metric
;

final class gauge extends generic
{
	function statsdMetricTemplateIs(template $template)
	{
		return $this->isGaugeAndStatsdMetricTemplateIs($template);
	}
}
