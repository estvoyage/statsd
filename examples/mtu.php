<?php

/*
This script send metrics to console according to MTU.
To use it:
1. Go to the root directory and install composer with `curl -sS https://getcomposer.org/installer | php`;
2. Execute `php composer.phar install`;
3. Go in the `examples` directory and execute `php localhost.php` to send metrics.
*/

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/classes/console.php';

use
	estvoyage\net,
	estvoyage\statsd,
	estvoyage\statsd\metric,
	estvoyage\statsd\metric\bucket,
	estvoyage\statsd\metric\value,
	estvoyage\statsd\metric\sampling
;

// MTU is 68
(new statsd\client\etsy(new metric\consumer\dataConsumerWithMtu(new console, new net\mtu(68))))
	->newStatsdMetric(
		(new metric\packet)
			->newStatsdMetric(new metric\counting(new bucket('gorets')))
			->newStatsdMetric(new metric\counting(new bucket('gorets'), new value(666)))
			->newStatsdMetric(new metric\timing(new bucket('glork'), new value(320)))
			->newStatsdMetric(new metric\timing(new bucket('glork'), new value(320), new sampling(.1)))
			->newStatsdMetric(new metric\gauge(new bucket('gaugor'), new value(333)))
			->newStatsdMetric(new metric\set(new bucket('uniques'), new value(765)))
	)
;

/* Output should be something like:
New metric: <gorets:1|c\ngorets:666|c\nglork:320|ms\nglork:320|ms|@0.1\ngaugor:333|g\n>
New metric: <uniques:765|s\n>
*/
