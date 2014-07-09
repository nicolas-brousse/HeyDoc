<?php

namespace HeyDoc\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('check')
            ->setDescription('Check your HeyDoc')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->checkFileContents('web/.htaccess');
        $this->checkFileContents('web/index.php');
    }

    private function checkFileContents($fileName)
    {
        $status = 'Not same';

        $fileToCheck = $this->getWorkingDirectory() . DIRECTORY_SEPARATOR . $fileName;
        $sourceDir   = realpath(__DIR__.'/../../../HeyDoc/Resources/templates/');

        if (! $this->fs->exists($fileToCheck)) {
            $status = "Doesn't exists";
        }
        else if (md5_file($fileToCheck) === md5_file($sourceDir . DIRECTORY_SEPARATOR . $fileName)) {
            $status = 'Ok';
        }

        $this->output->writeln(sprintf(
            '>> file <fg=yellow>%s</> is <fg=%s>%s</>',
            $fileName,
            mb_strtolower($status) === 'ok' ? 'green' : 'red',
            $status
        ));
    }
}
