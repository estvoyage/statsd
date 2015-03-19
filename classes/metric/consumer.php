<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\net,
	estvoyage\data
;

interface consumer
{
	function statsdMetricTemplateIs(template $metricTemplate);
	function newDataFromStatsdMetricTemplate(data\data $data, template $metricTemplate);
}
