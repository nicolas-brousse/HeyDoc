<?php

namespace HeyDoc\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SetupCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('setup')
            ->setDescription('Setup project')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // TODO
    }
}
