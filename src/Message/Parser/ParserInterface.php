<?php
namespace Xymon\Message\Parser;

interface ParserInterface
{
    /**
     * @param string $msg
     * @return MessageInterface
     */
    public static function parse($msg);
}
