<?php
namespace Xymon\Message;

use Xymon\Message\Parser\ParserInterface;
use Xymon\Utility\SimpleTokenizer;

/**
 * @author chey
 */
class Data extends Message implements ParserInterface
{
    use Traits\Hostname;

    const DEFINITION = [
        'command' => ' ',
        'hostname' => '.',
        'dataname' => "\n",
        'body' => ''
    ];

    /**
     * @return Data
     */
    public static function parse($msg)
    {
        return new self(SimpleTokenizer::interpret($msg, self::DEFINITION));
    }
}
