<?php

namespace HeyDoc\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SetupCommand extends Command
{
    private $filesystem;
    private $input;
    private $output;

    protected function configure()
    {
        $this
            ->setName('setup')
            ->setDescription('Setup project')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->filesystem = new Filesystem;
        $this->input      = $input;
        $this->output     = $output;
        $this->currentDir = getcwd();


        $this->createEmptyDir('docs');
        $this->copyFile('docs/settings.yml');
        $this->copyFile('docs/index.md');

        $this->createEmptyDir('web');
        $this->copyFile('web/.htaccess');
        $this->copyFile('web/index.php');
    }

    private function createEmptyDir($dirName)
    {
        $dirToCreate = $this->currentDir . DIRECTORY_SEPARATOR . $dirName;

        if ($this->filesystem->exists($dirToCreate)) {
            $this->output->writeln(sprintf('>> directory already exists <fg=blue>%s</>', $dirName));
            return;
        }

        $this->output->writeln(sprintf('>> create empty directory <fg=green>%s</>', $dirName));
        $this->filesystem->mkdir($dirToCreate, 0755);
    }

    private function copyFile($fileName)
    {
        $fileToCopy = $this->currentDir . DIRECTORY_SEPARATOR . $fileName;

        if ($this->filesystem->exists($fileToCopy)) {
            $this->output->writeln(sprintf('>> file already exists <fg=blue>%s</>', $fileName));
            return;
        }

        $sourceDir = realpath(__DIR__.'/../../../templates/');

        $this->output->writeln(sprintf('>> copy file <fg=green>%s</>', $fileName));
        $this->filesystem->copy($sourceDir . DIRECTORY_SEPARATOR . $fileName, $fileToCopy, false);
    }
}
