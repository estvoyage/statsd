# *estvoyage\statsd* [![Build Status](https://secure.travis-ci.org/estvoyage/statsd.png?branch=master)](http://travis-ci.org/estvoyage/statsd) [![Coverage Status](https://coveralls.io/repos/estvoyage/statsd/badge.png)](https://coveralls.io/r/estvoyage/statsd)

## An east-oriented StatsD client written in PHP!

Just like [statsd-php](https://github.com/domnikl/statsd-php), *estvoyage\statsd*  is a [StatsD](https://github.com/etsy/statsd/wiki) client written in PHP.  
However, it was designed using the east compass, so all public method in all classes return `$this`, a new instance of the class or any value which is not a property of the class.  
Why? Because the rigorous application of this unique rule decreases coupling and the amount of code that needs to be written, while increasing the clarity, cohesion, flexibility, reuse and testability of that code.  
In fact, using east-oriented principle force using abstraction via interface and the lack of getter force using the *tell, don't ask* principle, inversion of control, depedency injection and interfaces.  

## Installation

Minimal PHP version to use **serialization** is 5.6.  
The recommended way to install Negotiation is through [Composer](http://getcomposer.org/), just add this in your `composer.json` and execute `php composer.phar install`:

``` json
{
    "require": {
        "estvoyage/statsd": "@dev"
    }
}
```

## Usage

In a nutshell:

``` php
<?php

require __DIR__ . '/../vendor/autoload.php';

use
	estvoyage\data
	estvoyage\statsd,
	estvoyage\statsd\metric,
	estvoyage\statsd\metric\bucket,
	estvoyage\statsd\metric\value,
	estvoyage\statsd\metric\sampling
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
```

A working script is available in the bundled `examples` directory, just do `php examples/nutshell.php` to execute it.
The `examples` directory contains serveral other examples about packet, mtu, socket and metric provider.

## Unit Tests

Setup the test suite using Composer:

    $ composer install --dev

Run it using **atoum**:

    $ vendor/bin/atoum

## Contributing

See the bundled `CONTRIBUTING` file for details.

## License

*estvoyage\statsd* is released under the FreeBSD License, see the bundled `COPYING` file for details.
