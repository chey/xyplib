<?php
namespace Xymon\Message\Parser;

use Xymon\Message\UnknownMessage;
use Xymon\Message\MessageDetector;
use Xymon\Utility\SimpleTokenizer;

class UnknownMessageParser implements ParserInterface
{
    public static function parse($msg)
    {
        $tokens = [
            'command' => MessageDetector::CMD_DELIM,
            'unknown' => '' // rest of message
        ];

        $data = SimpleTokenizer::interpret($msg, $tokens);
        $command = array_shift($data);

        return UnknownMessage::create($command, $data);
    }
}
