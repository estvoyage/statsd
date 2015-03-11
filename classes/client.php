<?php

namespace estvoyage\statsd;

interface client
{
	function statsdMetricProviderIs(metric\provider $provider);
}
