<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\statsd,
	estvoyage\statsd\metric
;

final class set extends generic
{
	function statsdMetricTemplateIs(template $template)
	{
		return $this->isSetAndStatsdMetricTemplateIs($template);
	}
}
