<?php

namespace HeydDoc;

use Symfony\Component\Finder\SplFileInfo;

class Page
{
    protected $file;
    protected $parent;
    protected $content;
    protected $fileContent;

    public function __construct(SplFileInfo $file, Page $parent = null)
    {
        $this->file   = $file;
        $this->parent = $parent;
    }

    public function parseFromMarkdown()
    {
        $markdownParser = new MarkdownExtraParser();
        $markdownParser->transformMarkdown($this->file->getContents());
    }
}
