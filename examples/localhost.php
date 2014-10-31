<?php

/*
This script send three different type of metric to a local statsd server which listen on its default port, aka 8125.
1. Go to the root directory and install composer with `curl -sS https://getcomposer.org/installer | php`
2. Execute `php composer.phar install`
3. Install statsd on your localhost or execute `nc -u -l 8125` in a terminal
4. Go in the `examples` directory and execute `php localhost.php` to send metrics to your local statsd server or to `nc`.
*/

require __DIR__ . '/../vendor/autoload.php';

use
	estvoyage\statsd,
	estvoyage\statsd\connection,
	estvoyage\statsd\metric
;

(new statsd\packet)
	->adds([ new metric\timing('speed1', 1), new metric\gauge('gauge1', 5), new metric\timing('speed2', 3) ])
	->writeOn(new connection\internet(new statsd\address))
;
