<?php

namespace estvoyage\statsd\tests\units;

if (defined('atoum\scripts\runner') === false)
{
	define('atoum\scripts\runner', __FILE__);
}

ini_set('include_path', '.:' . __DIR__);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/atoum/atoum/scripts/runner.php';
