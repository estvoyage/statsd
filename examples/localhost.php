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
	estvoyage\statsd\client,
	estvoyage\statsd\connection,
	estvoyage\statsd\address,
	estvoyage\statsd\packet,
	estvoyage\statsd\metric
;

(new client(new connection\intranet(new address)))
	->send(new metric\counting('counting1', 1))
	->send(new metric\gauge('gauge1', 100))
	->send(new metric\timing('timing1', 1))
	->send(new packet([ new metric\gauge('gauge2', 5), new metric\gauge('gauge3', 10) ]))
	->send(new metric\timing('timing1', 2))
	->send(new metric\gauge('gauge4', 100))
;
