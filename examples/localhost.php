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
	estvoyage\net,
	estvoyage\statsd,
	estvoyage\statsd\metric\bucket,
	estvoyage\statsd\metric\value
;

#TODO Uncomment when connection builder was ready.
(new statsd\client(new statsd\connection(new net\socket\client\sockets\udp(new net\host('127.0.0.1'), new net\port(8125)))))

	->valueGoesInto(value::counting(), new bucket(uniqid()))
	->valueGoesInto(value::counting(-1), new bucket(uniqid()))
	->valueGoesInto(value::counting(42), new bucket(uniqid()))
	->valueGoesInto(value::counting(666, rand(1, 100) / 1000), new bucket(uniqid()))
	->valueGoesInto(value::counting(- 999, rand(1, 100) / 1000), new bucket(uniqid()))
;
