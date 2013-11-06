<?php

namespace HeyDoc\Console\Command;

use HeyDoc\Page;
use HeyDoc\Tree;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExportCommand extends Command
{
    private $exportDir = '_export';
    private $force     = false;

    protected function configure()
    {
        $this
            ->setName('export')
            ->setDescription('Export project')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force page generation if file already exists')
            ->addArgument('export_dir', InputArgument::OPTIONAL, 'Directory to export pages')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command export page in html format
into _export folder by default:

    <info>php %command.full_name%</info>

The <comment>export_dir</comment> argument permit to precise
a directory to export:

    <info>php %command.full_name% /path/to/export</info>

The <comment>--force</comment> option force to write page
if already exists:

    <info>php %command.full_name% --force</info>
EOF
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('force')) {
            $this->force = true;
        }

        if ($a = $input->getArgument('export_dir')) {
            $this->exportDir = $a;
        }
        $this->exportDir = $this->currentDir = getcwd() . DIRECTORY_SEPARATOR . $this->exportDir;

        $output->writeln(sprintf('Export pages in <fg=yellow>%s</>', $this->exportDir));
        $output->writeln('');

        $tree = $this->container->get('tree');
        $this->exportTree($tree);
    }

    private function exportTree(Tree $tree)
    {
        $this->createEmptyDir($tree->getUrl());

        foreach ($tree->getChildren() as $child) {
            $this->exportTree($child);
        }

        foreach ($tree->getPages() as $page) {
            $this->exportPage($page);
        }
    }

    private function exportPage(Page $page)
    {
        // call renderer to generate content
        $content = $this->container->get('renderer')->render($page);

        // Build filename
        $filename = $this->exportDir . $page->getUrl();
        if (basename($filename) !== 'index') {
            $this->createEmptyDir($page->getUrl());
            $filename .= '/index';
        }
        $filename .= '.html';

        // TODO: If file exist prompt for replace
        if (! $this->force && $this->fs->exists($filename))
        {
            $this->output->writeln(sprintf('>> page already exists <fg=blue>%s</>', $filename));
            if (! $this->dialog->askConfirmation($this->output, '  <question>Do you want to overwrite it?</question>', false)) {
                return;
            }
        }

        $this->fs->touch($filename);
        $file = new \SplFileObject($filename, 'w');
        $file->fwrite($content);

        $this->output->writeln(sprintf('>> create page <fg=green>%s</>', $filename));
    }
}
