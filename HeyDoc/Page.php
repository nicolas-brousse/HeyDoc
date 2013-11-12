<?php

namespace HeyDoc;

class Page
{
    const FORMAT_MARKDOWN = 'markdown';
    const FORMAT_HTML     = 'html';

    /** @var SplFileInfo  $file  File for this page **/
    protected $file;

    /** @var Tree  $tree  The Tree for this page **/
    protected $tree;

    /** @var string  $content  Content of this Page **/
    protected $content;

    /** @var string  $format  Content format of this Page **/
    protected $format;

    /** @var DateTime  $container  Date of page is last madified at **/
    protected $updatedAt;

    /** @var ArrayObject  $container  Headers **/
    protected $headers;


    /**
     * Construct.
     *
     * @param SplFileInfo  $file  File for this page
     * @param Tree         $tree  The Tree for this page
     */
    public function __construct(\SplFileInfo $file, Tree $tree)
    {
        $this->file = $file;
        $this->tree = $tree;

        $this->headers = new \ArrayObject();
        $this->content = '';

        $this->load();
    }

    /**
     * Get the title of the page
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->headers->offsetExists('title')
            ? $this->headers->offsetGet('title')
            : ucfirst(str_replace('_', ' ', $this->getName()))
        ;
    }

    /**
     * Get the layout name of the page
     *
     * @return string
     */
    public function getLayout()
    {
        return $this->headers->offsetExists('layout') ? $this->headers->offsetGet('layout') : null;
    }

    /**
     * Get the content of the page
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Get the name of the page (is based on filename)
     *
     * @return string
     */
    public function getName()
    {
        $name = $this->file->getBasename('.' . $this->getExtension());
        return preg_replace("/^\d+_/", '', $name);
    }

    /**
     * Get the last modified date of the page
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        if (! $this->updatedAt) {
            $this->updatedAt = \DateTime::createFromFormat('U', $this->file->getMTime());
        }
        return $this->updatedAt;
    }

    /**
     * Get the headers of the page
     *
     * @return ArrayObject
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get the url of the page
     *
     * @return string
     */
    public function getUrl()
    {
        $name = $this->getName() == 'index' ? '' : $this->getName() . '/';
        return $this->tree->getUrl() . '/' . $name;
    }

    /**
     * Get the format of the page
     *
     * @return string
     */
    public function getFormat()
    {
        switch ($this->getExtension()) {
            case 'md':
            case 'markdown':
                return self::FORMAT_MARKDOWN;

            case 'html':
            case 'htm':
                return self::FORMAT_HTML;
        }
    }

    /**
     * Get the extension of the page
     *
     * @return string
     */
    protected function getExtension()
    {
        return pathinfo($this->file->getFilename(), PATHINFO_EXTENSION);
    }

    /**
     * Reload the page
     */
    public function refresh()
    {
        $this->headers = new \ArrayObject();
        $this->content = '';

        $this->load();
    }

    /**
     * Load the page
     */
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
                    // @todo  Create an explain Exception
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
