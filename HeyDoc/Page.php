<?php

namespace HeyDoc;

use Symfony\Component\Finder\SplFileInfo;

class Page
{
    protected $file;
    protected $tree;
    protected $content;

    protected $headers = array();

    public function __construct(SplFileInfo $file, Tree $tree)
    {
        $this->file = $file;
        $this->tree = $tree;

        $this->load();
    }

    public function getTitle()
    {
        return array_key_exists('title', $this->headers) ? $this->headers['title'] : ucfirst($this->getName());
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getName()
    {
        return $this->file->getBasename('.' . $this->file->getExtension());
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getUrl()
    {
        return $this->tree->getUrl() . '/' . $this->getName();
    }

    public function refresh()
    {
        $this->load();
    }

    protected function load()
    {
        $hasHeader = false;
        $isHeader  = false;

        $contents = array();

        foreach ($this->file->openFile() as $i=>$line)
        {
            if (substr($line, 0, 3) === '---')
            {
                if ($i === 0) {
                    $isHeader  = true;
                    $hasHeader = true;
                }
                else {
                    $isHeader = false;
                }

                continue;
            }

            if ($hasHeader && $isHeader)
            {
                $d = array_map('trim', explode(':', $line));

                if (count($d) != 2) {
                    // TODO
                    throw new Exception("Error Processing Request", 1);
                }

                $this->headers[mb_strtolower($d[0])] = $d[1];
            }
            else {
                $contents[] = $line;
            }
        }

        $this->content = implode("\n", $contents);
    }
}
