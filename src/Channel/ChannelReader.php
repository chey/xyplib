<?php
namespace Xymon\Channel;

use Xymon\Utility\Helper;

/**
 * Information about the status message types can be found in the Xymon
 * source within the file named: xymond/new-daemon.txt
 */
class ChannelReader
{
    /**
     * @var string
     */
    const MSG_SEP = '@@';

    /**
     * @var string
     */
    const MSG_BEGIN = self::MSG_SEP;

    /**
     * @var string
     */
    const MSG_END = self::MSG_SEP . "\n";

    /**
     * Read Xymond messages from file pointer.
     */
    public static function get($timeout = false, $fp = STDIN) {
        $msg = null;

        $maxline = (int) (getenv('MAXLINE') ? getenv('MAXLINE') : 32768);

        $startTime = \Xymon\milliseconds();

        $line = fgets($fp, $maxline);

        // We don't want to start with the end of a message
        if ($line === false || $line === self::MSG_END) {
            return null;
        }

        // Find out if it's the beginning of a message
        if (strncmp($line, self::MSG_SEP, 2) !== 0) {
            return null;
        }

        // Make sure there is substance to the start of the message
        if (strlen($line) < 3) {
            return null;
        }

        // Make sure the first line ends with a newline
        if (substr($line, -1) !== "\n") {
            $line .= "\n";
        }

        // Strip out the @@ from the start of a message
        $line = substr($line, 2);

        // Get the rest of the message
        while ($line !== false && $line !== self::MSG_END) {
            $msg .= $line;

            // Stop if it's been too long
            if ($timeout && \Xymon\milliseconds() > ($startTime + $timeout)) {
                break;
            }

            $line = fgets($fp, $maxline);
        }

        return $msg;
    }

    public static function parse($msg)
    {
        $channel = strtok($msg, '#');
        $sequence = strtok('/');

        $newline = strpos($msg, "\n");

        if ($newline !== false) {
            $metadata = Helper::parseLine(strtok("\n"));
        }

        if ($newline !== false) {
            $body = (string) substr($msg, $newline+1);
        }

        return compact('channel', 'sequence', 'metadata', 'body');
    }
}
