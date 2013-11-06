<?php

namespace HeyDoc;

use Symfony\Component\Finder\SplFileInfo;

class Page
{
    const FORMAT_MARKDOWN = 'markdown';
    const FORMAT_HTML     = 'html';

    protected $file;
    protected $tree;
    protected $content;
    protected $format;
    protected $updatedAt;

    protected $headers;

    public function __construct(SplFileInfo $file, Tree $tree)
    {
        $this->file = $file;
        $this->tree = $tree;

        $this->headers = new \ArrayObject();
        $this->content = '';

        $this->load();
    }

    public function getTitle()
    {
        return $this->headers->offsetExists('title') ? $this->headers->offsetGet('title') : ucfirst($this->getName());
    }

    public function getLayout()
    {
        return $this->headers->offsetExists('layout') ? $this->headers->offsetGet('layout') : null;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getName()
    {
        return $this->file->getBasename('.' . $this->file->getExtension());
    }

    public function getUpdatedAt()
    {
        if (! $this->updatedAt) {
            $this->updatedAt = new DateTime($this->file->getMTime());
        }
        return $this->updatedAt;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getUrl()
    {
        return $this->tree->getUrl() . '/' . $this->getName();
    }

    public function getFormat()
    {
        switch ($this->file->getExtension()) {
            case 'md':
            case 'markdown':
                return self::FORMAT_MARKDOWN;

            case 'html':
            case 'htm':
                return self::FORMAT_HTML;
        }
    }

    public function refresh()
    {
        $this->headers = new \ArrayObject();
        $this->content = '';

        $this->load();
    }

    protected function load()
    {
        $hasHeader = false;
        $isHeader  = false;
        $contents  = new \ArrayObject();

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
                    // TODO create an explain Exception
                    throw new \Exception(sprintf('Headers format invalid for "%s" file', $this->file->getPathname()));
                }

                $this->headers->offsetSet(mb_strtolower($d[0]), $d[1]);
            }
            else {
                $contents->append($line);
            }
        }

        $this->content = implode("\n", $contents->getArrayCopy());
    }
}
