<?php

namespace HeyDoc\Parser;

class Markdown extends \MarkdownExtraExtended_Parser
{
    public function transform($text)
    {
        $text = parent::transform($text);

        return $text;
    }
}
