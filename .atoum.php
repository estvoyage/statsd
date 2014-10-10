<?php

$runner
	->addTestsFromDirectory(__DIR__ . '/tests/units/classes')
	->disallowUndefinedMethodInInterface()
;
