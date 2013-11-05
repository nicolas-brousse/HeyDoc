<?php

use HeyDoc\Application;

require_once realpath(__DIR__.'/../vendor/autoload.php');

$application = new Application(__DIR__);
$application->run();
