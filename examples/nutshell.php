<?php

/*
This script send metrics to a console
To use it:
1. Go to the root directory and install composer with `curl -sS https://getcomposer.org/installer | php`;
2. Execute `php composer.phar install`;
3. Go in the `examples` directory and execute `php localhost.php` to send metrics.
*/

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/classes/console.php';

use
	estvoyage\statsd,
	estvoyage\statsd\metric,
	estvoyage\statsd\metric\bucket,
	estvoyage\statsd\metric\value,
	estvoyage\statsd\metric\sampling
;

(new statsd\client\etsy(new metric\consumer\dataConsumer(new console)))
	->newStatsdMetric(new metric\counting(new bucket('gorets')))
	->newStatsdMetric(new metric\counting(new bucket('gorets'), new value(666)))
	->newStatsdMetric(new metric\counting(new bucket('gorets'), new value(999), new sampling(.1)))
	->newStatsdMetric(new metric\timing(new bucket('glork'), new value(320)))
	->newStatsdMetric(new metric\timing(new bucket('glork'), new value(320), new sampling(.1)))
	->newStatsdMetric(new metric\gauge(new bucket('gaugor'), new value(333)))
	->newStatsdMetric(new metric\set(new bucket('uniques'), new value(765)))
;

/* Output should be something like:
New metric: <gorets:1|c\n>
New metric: <gorets:666|c\n>
New metric: <gorets:999|c|@0.1\n>
New metric: <glork:320|ms\n>
New metric: <glork:320|ms|@0.1\n>
New metric: <gaugor:333|g\n>
New metric: <uniques:765|s\n>
*/
