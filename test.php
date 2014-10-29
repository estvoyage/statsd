<?php

require __DIR__ . '/vendor/autoload.php';

use estvoyage\statsd;

$connection = new statsd\connection\internet(new statsd\address(new statsd\host\localhost, new statsd\port\statsd));

(new statsd\packet)
	->add(new statsd\metric\timing('speed1', 1))
	->add(new statsd\metric\gauge('gauge1', 5))
	->add(new statsd\metric\timing('speed2', 3))
	->writeOn($connection, function() {})
;
