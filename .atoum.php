<?php

require __DIR__ . '/vendor/autoload.php';

$runner
	->addTestsFromDirectory(__DIR__ . '/tests/units/classes')
	->disallowUndefinedMethodInInterface()
;
