<?php

namespace HeyDoc\Console;

use Symfony\Component\Console\Application as BaseApplication;

use HeyDoc\Console\Command\CheckCommand;
use HeyDoc\Console\Command\ExportCommand;
use HeyDoc\Console\Command\SetupCommand;

class Application extends BaseApplication
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        error_reporting(-1);

        parent::__construct('HeyDoc');

        $this->add(new SetupCommand());
        $this->add(new ExportCommand());
        $this->add(new CheckCommand());
    }
}
