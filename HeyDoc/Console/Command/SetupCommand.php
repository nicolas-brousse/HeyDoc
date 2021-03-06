<?php

namespace HeyDoc\Console\Command;

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
        // @todo  Use Finder to list files into templates dir and copy this files
        $this->createEmptyDir('docs');
        $this->copyFile('docs/settings.yml');
        $this->copyFile('docs/00_index.md');

        $this->createEmptyDir('web');
        $this->copyFile('web/.htaccess');
        $this->copyFile('web/index.php');

        $this->createEmptyDir('cache', 0777);
    }

    private function copyFile($fileName)
    {
        $fileToCopy = $this->getWorkingDirectory() . DIRECTORY_SEPARATOR . $fileName;

        if ($this->fs->exists($fileToCopy)) {
            $this->output->writeln(sprintf('>> file already exists <fg=blue>%s</>', $fileName));
            return;
        }

        $sourceDir = realpath(__DIR__.'/../../../HeyDoc/Resources/templates/');

        $this->fs->copy($sourceDir . DIRECTORY_SEPARATOR . $fileName, $fileToCopy, false);
        $this->output->writeln(sprintf('>> copy file <fg=green>%s</>', $fileName));
    }
}
