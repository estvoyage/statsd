<?php

/*
This script send metrics to local udp socket on port 8125.
To use it:
1. Go to the root directory and install composer with `curl -sS https://getcomposer.org/installer | php`;
2. Execute `php composer.phar install`;
3. Install statsd on your localhost or execute `nc -u -l 8125` (or `nc -u -l -p 8125` on OSX) in a terminal;
4. Go in the `examples` directory and execute `php localhost.php` to send metrics.

WARNING: This file throw an exception if the server socket is unavailable!
*/

require __DIR__ . '/../vendor/autoload.php';

use
	estvoyage\net,
	estvoyage\data,
	estvoyage\statsd,
	estvoyage\statsd\metric,
	estvoyage\statsd\metric\bucket,
	estvoyage\statsd\metric\value,
	estvoyage\statsd\metric\sampling
;

(new statsd\client\etsy(new metric\consumer\dataConsumer(new net\socket\client\native\udp(new net\host('127.0.0.1'), new net\port(8125)))))
	->newStatsdMetric(new metric\counting(new bucket('gorets')))
	->newStatsdMetric(new metric\counting(new bucket('gorets'), new value(666)))
	->newStatsdMetric(new metric\counting(new bucket('gorets'), new value(999), new sampling(.1)))
	->newStatsdMetric(new metric\timing(new bucket('glork'), new value(320)))
	->newStatsdMetric(new metric\timing(new bucket('glork'), new value(320), new sampling(.1)))
	->newStatsdMetric(new metric\gauge(new bucket('gaugor'), new value(333)))
	->newStatsdMetric(new metric\set(new bucket('uniques'), new value(765)))
;

/* Output should be something like:
$ nc -u -l -p 8125
gorets:1|c
gorets:666|c
gorets:999|c|@0.1
glork:320|ms
glork:320|ms|@0.1
gaugor:333|g
uniques:765|s
*/
