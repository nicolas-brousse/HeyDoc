<?php

namespace HeyDoc\Console\Command;

use HeyDoc\Console\Container;
use HeyDoc\ErrorHandler;
use HeyDoc\Request;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends BaseCommand
{
    protected $container;
    protected $input;
    protected $output;
    protected $dialog;

    private $workingDirectory;

    protected $fs;

    /**
     *
     *
     * @param InputInterface   $input
     * @param OutputInterface  $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        if (OutputInterface::VERBOSITY_DEBUG <= $output->getVerbosity()) {
            ErrorHandler::register(true);
        }

        $this->input  = $input;
        $this->output = $output;
        $this->dialog = $this->getHelperSet()->get('dialog');

        $this->workingDirectory = getcwd();

        $this->container = new Container();
        $this->container->setRequest(new Request());
        $this->container->setWebDir(getcwd() . DIRECTORY_SEPARATOR . 'web');
        $this->container->load();

        $this->fs = $this->container->get('fs');
    }

    /**
     *
     */
    protected function setWorkingDirectory($directory)
    {
        // if (is_dir($directory)) {
            $this->workingDirectory = $directory;
        // }
    }

    /**
     *
     */
    protected function getWorkingDirectory()
    {
        return $this->workingDirectory;
    }

    /**
     *
     *
     * @param string  $dirName  Directory to create
     */
    protected function createEmptyDir($dirName, $mode = 0755)
    {
        $dirToCreate = $this->workingDirectory . DIRECTORY_SEPARATOR . $dirName;

        if ($this->fs->exists($dirToCreate)) {
            $this->output->writeln(sprintf('>> directory already exists <fg=blue>%s</>', $dirName));
            return;
        }

        $this->fs->mkdir($dirToCreate, $mode);
        $this->output->writeln(sprintf('>> create empty directory <fg=green>%s</>', $dirName));
    }
}
