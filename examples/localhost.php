<?php

/*
This script send metrics to a console or a any local udp socket on port 8125.
To use it:
1. Go to the root directory and install composer with `curl -sS https://getcomposer.org/installer | php`;
2. Execute `php composer.phar install`;
3. Install statsd on your localhost or execute `nc -u -l 8125` (or `nc -u -l -p 8125` on OSX) in a terminal;
4. Go in the `examples` directory and execute `php localhost.php` to send metrics.
*/

require __DIR__ . '/../vendor/autoload.php';

use
	estvoyage\net,
	estvoyage\data,
	estvoyage\statsd,
	estvoyage\statsd\metric,
	estvoyage\statsd\metric\bucket,
	estvoyage\statsd\metric\value
;

class console implements data\consumer
{
	function dataProviderIs(data\provider $dataProvider)
	{
		$dataProvider->dataConsumerIs($this);

		return $this;
	}

	function newData(data\data $data)
	{
		echo 'New metric: <' . str_replace("\n", '\n' , $data) . '>' . PHP_EOL;

		return $this;
	}

	function noMoreData()
	{
		return $this;
	}
}

(new statsd\client\etsy(new metric\consumer\dataConsumer(new console)))
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
;

$packet = (new metric\packet)
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
;

(new statsd\client\etsy(new metric\consumer\dataConsumer(new console)))
	->newStatsdMetric($packet)
;

$packet = (new metric\packet)
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
;

(new statsd\client\etsy(new metric\consumer\dataConsumerWithMtu(new console, new net\mtu(68))))
	->newStatsdMetric($packet)
;

$packet = (new metric\packet)
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
;

$packet->statsdClientIs(new statsd\client\etsy(new metric\consumer\dataConsumer(new console)));

(new statsd\client\etsy(new metric\consumer\dataConsumer(new net\socket\client\native\udp(new net\host('127.0.0.1'), new net\port(8125)))))
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
;

/* Output should be something like:
New metric: <550b3df58f0f3:1|c\n>
New metric: <550b3df58f33c:1|c\n550b3df58f35d:1|c\n550b3df58f374:1|c\n550b3df58f38a:1|c\n550b3df58f3a1:1|c\n>
New metric: <550b3df58f466:1|c\n550b3df58f47f:1|c\n550b3df58f493:1|c\n>
New metric: <550b3df58f4a8:1|c\n550b3df58f4bd:1|c\n>
New metric: <550b3df58f80c:1|c\n550b3df58f829:1|c\n550b3df58f83e:1|c\n550b3df58f853:1|c\n550b3df58f867:1|c\n>
*/
