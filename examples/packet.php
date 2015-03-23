<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/classes/console.php';

use
	estvoyage\net,
	estvoyage\data,
	estvoyage\statsd,
	estvoyage\statsd\metric,
	estvoyage\statsd\metric\bucket,
	estvoyage\statsd\metric\value,
	estvoyage\statsd\metric\sampling
;

// Just a packet
(new statsd\client\etsy(new metric\consumer\dataConsumer(new console)))
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

// parent bucket of packet is 'myapp'
(new statsd\client\etsy(new metric\consumer\dataConsumer(new console), new metric\bucket('myapp')))
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

// Packet is a metric provider, so you can pass it a statsd client
(new metric\packet)
	->newStatsdMetric(new metric\counting(new bucket('gorets')))
	->newStatsdMetric(new metric\counting(new bucket('gorets'), new value(666)))
	->newStatsdMetric(new metric\timing(new bucket('glork'), new value(320)))
	->newStatsdMetric(new metric\timing(new bucket('glork'), new value(320), new sampling(.1)))
	->newStatsdMetric(new metric\gauge(new bucket('gaugor'), new value(333)))
	->newStatsdMetric(new metric\set(new bucket('uniques'), new value(765)))
		->statsdClientIs(new statsd\client\etsy(new metric\consumer\dataConsumer(new console)))
;

/* Output should be something like:
New metric: <gorets:1|c\ngorets:666|c\nglork:320|ms\nglork:320|ms|@0.1\ngaugor:333|g\nuniques:765|s\n>
New metric: <myapp.gorets:1|c\nmyapp.gorets:666|c\nmyapp.glork:320|ms\nmyapp.glork:320|ms|@0.1\nmyapp.gaugor:333|g\nmyapp.uniques:765|s\n>
New metric: <gorets:1|c\ngorets:666|c\nglork:320|ms\nglork:320|ms|@0.1\ngaugor:333|g\nuniques:765|s\n>
*/
