<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\statsd,
	estvoyage\statsd\metric
;

final class packet implements statsd\metric
{
	private
		$metrics
	;

	function newMetric(metric $metric)
	{
		$this->metrics[] = $metric;

		return $this;
	}

	function statsdMetricTemplateIs(metric\template $template)
	{
		if ($this->metrics)
		{
			foreach ($this->metrics as $metric)
			{
				$template->newStatsdMetric($metric);
			}

			$this->metrics = null;
		}

		return $this;
	}
}
