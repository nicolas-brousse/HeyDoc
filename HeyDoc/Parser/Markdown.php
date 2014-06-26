<?php

namespace HeyDoc\Parser;

class Markdown extends \ParsedownExtra
{
    public function transform($text)
    {
        return $this->text($text);
    }
}
