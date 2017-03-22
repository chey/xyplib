<?php
namespace Xymon\Message\Parser;

use Xymon\Message\Status;
use Xymon\Message\MessageType;

class StatusParser implements ParserInterface
{
    /**
     * @param string $msg
     * @return Xymon\Message\Status
     */
    public static function parse($msg)
    {
        $command = $hostname = $testname = $color = $lifetime = $group = $summary = $body = null;

        if (strncmp($msg, MessageType::Status, 6) !== 0) {
            throw new \UnexpectedValueException('Not a status message');
        }

        $sections = preg_split('/\s+/', strtok($msg, "\n\r"), 4);

        if (false !== strpos($sections[0], '/')) {
            list($s0a, $s0b) = explode('/', $sections[0], 2);
            list($id, $group) = explode(':', $s0b);
        } else {
            $s0a = $sections[0];
        }

        if (false !== strpos($s0a, '+')) {
            list($command, $lifetime) = explode('+', $s0a);
        }

        $dot = strrpos($sections[1], '.');
        $hostname = substr($sections[1], 0, $dot);
        $testname = substr($sections[1], $dot+1);

        $color = $sections[2];

        if (isset($sections[3])) {
            $summary = $sections[3];
        }

        $body = substr($msg, strpos($msg, "\n")+1);

        return new Status(compact('hostname', 'testname', 'color', 'lifetime', 'group', 'summary', 'body'));
    }
}
