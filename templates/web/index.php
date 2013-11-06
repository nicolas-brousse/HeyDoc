<?php

use HeyDoc\Application;
use Symfony\Component\HttpFoundation\Request;

require_once realpath(__DIR__.'/../vendor/autoload.php');

$request = Request::createFromGlobals();
$application = new Application(__DIR__, $request);
$application->run();
