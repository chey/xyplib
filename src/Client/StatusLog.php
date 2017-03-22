<?php
namespace Xymon\Client;

use Xymon\Utility\Helper;
use Xymon\Message\Xymondlog as Message;
use Xymon\Message\MessageType;

/**
 * Query xymondlog for a specific host and test.
 * 
 * @uses Client
 * @author chey
 */
class StatusLog extends Client {
    /**
     * Get status log for a host.
     * 
     * @param string $hostname 
     * @param string $testname 
     * @return array associative array keys and values
     */
    public function get($hostname, $testname)
    {
        return array_combine(
            Helper::DEFAULT_FIELDS[MessageType::Xymondlog],
            $this->cast($this->execute(new Message(compact('hostname', 'testname')))->getBody())
        );
    }

    public function cast($status)
    {
        $line1 = substr($status, 0, strpos($status, "\n") + 1);

        $arr = Helper::parseLine($line1);

        $ptr =& $arr[count($arr) - 1];

        $ptr = substr($status, strpos($status, "\n") + 1);

        return $arr;
    }
}
