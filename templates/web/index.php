<?php

use HeyDoc\Application;
use HeyDoc\Request;

require_once realpath(__DIR__.'/../vendor/autoload.php');

$request = Request::createFromGlobals();
$application = new Application($request);
$application->run();
