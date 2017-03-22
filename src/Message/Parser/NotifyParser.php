<?php
namespace Xymon\Message\Parser;

use Xymon\Message\Notify;
use Xymon\Utility\SimpleTokenizer;

class NotifyParser implements ParserInterface
{
    /**
     * @param string $msg
     * @return Notify
     */
    public static function parse($msg)
    {
        return new Notify(SimpleTokenizer::interpret($msg, Notify::DEFINITION));
    }
}
