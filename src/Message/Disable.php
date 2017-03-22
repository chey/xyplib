<?php
namespace Xymon\Message;

use Xymon\Message\Parser\ParserInterface;
use Xymon\Utility\SimpleTokenizer;

/**
 * @author chey
 */
class Disable extends Message implements ParserInterface
{
    use Traits\Hostname;
    use Traits\Testname;

    const DEFINITION = [
        'command' => ' ',
        'hostname' => '.',
        'testname' => ' ',
        'duration' => ' ',
        'body' => ''
    ];

    /**
     * @return Disable
     */
    public static function parse($msg)
    {
        return new self(SimpleTokenizer::interpret($msg, self::DEFINITION));
    }
}
