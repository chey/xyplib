<?php
namespace Xymon\Client;

use Xymon\Message\Clientlog as Message;
use Xymon\Utility\Helper;

/**
 * Class used for communicating with Xymond using 'clientlog' command.
 * 
 * @author chey
 * @uses Client
 */
class ClientLog extends Client
{
    /**
     * Query xymond using 'clientlog' command.
     * 
     * @param string $hostname 
     * @param array $sections
     * @return array
     */
    public function get($hostname, $sections = array())
    {
        $message = new Message;
        $message->setHostname($hostname);
        $message->section($sections);

        return self::parse($this->execute($message)->getBody());
    }

    /**
     * @param string $log result to be parsed
     * @return array of data keyed by the name of the section
     */
    public static function parse($log)
    {
        $output = array();
        $separator = "\r\n";

        $line = strtok($log, $separator);
        while ($line !== false) {
            if ($line{0} === '[') {
                $key = substr($line, 1, strlen($line) - 2);
                $ptr =& $output[$key];
            } else {
                $ptr[] = $line;
            }

            $line = strtok($separator);
        }

        foreach ($output as $key => $val) {
            $output[$key] = implode("\n", (array) $val);
        }
        
        return (array) $output;
    }
}
