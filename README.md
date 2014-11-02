# *estvoyage\statsd* [![Build Status](https://secure.travis-ci.org/estvoyage/statsd.png?branch=master)](http://travis-ci.org/estvoyage/statsd)

## An east-oriented StatsD client written in PHP!

Just like [statsd-php](https://github.com/domnikl/statsd-php), *estvoyage\statsd*  is a [StatsD](https://github.com/etsy/statsd/wiki) client written in PHP.  
However, it was designed using the east compass, so all public method in all classes return `$this`, a new instance of the class or any value which is not a property of the class.  
Why? Because the rigorous application of this unique rule decreases coupling and the amount of code that needs to be written, while increasing the clarity, cohesion, flexibility, reuse and testability of that code.  
In fact, using east-oriented principle force using abstraction via interface and the lack of getter force using the *tell, don't ask* principle.  

## Prerequisites to use *estvoyage\statsd*

*estvoyage\statsd* absolutely requires *PHP 5.5* or later to work.
On UNIX, in order to check whether you have the right PHP version, you just need to run the following command in your terminal :

	# php -r 'echo (version_compare(PHP_VERSION, "5.5.0", ">=") ? "You can use estvoyage\statsd!" : "You can not use estvoyage\statsd, please upgrade PHP to its last version") . PHP_EOL;'

If `You can use estvoyage\statsd!` or equivalent gets displayed, then you have the right PHP version installed.
If it's not the case, please upgrade your PHP version (see [PHP web site](http://www.php.net) for more information).

## Installation

Coming soon…

## How to contribute?

Coming soon… or maybe you can do a pull request to improve this README?
