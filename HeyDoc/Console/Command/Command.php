<?php

namespace HeyDoc\Console\Command;

use HeyDoc\Container;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class Command extends BaseCommand
{
    protected $container;
    protected $input;
    protected $output;

    protected $currentDir;

    protected $fs;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->input  = $input;
        $this->output = $output;

        $this->currentDir = getcwd();

        $this->fs = new Filesystem;

        $this->container = new Container();
        $this->container->setWebDir(getcwd() . DIRECTORY_SEPARATOR . 'web');
        $this->container->load();
    }

    protected function createEmptyDir($dirName)
    {
        $dirToCreate = $this->currentDir . DIRECTORY_SEPARATOR . $dirName;

        if ($this->fs->exists($dirToCreate)) {
            $this->output->writeln(sprintf('>> directory already exists <fg=blue>%s</>', $dirName));
            return;
        }

        $this->fs->mkdir($dirToCreate, 0755);
        $this->output->writeln(sprintf('>> create empty directory <fg=green>%s</>', $dirName));
    }
}
