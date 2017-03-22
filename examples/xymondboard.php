<?php
/**
 * Working example of the XymondBoard client.
 *
 * @author chey
 */

require 'vendor/autoload.php';

use Xymon\Client\Board as XymondBoard;

$uri = 'http://localhost/xymon-cgimsg/xymoncgimsg.cgi';

if (empty($argv[1])) {
    die("Supply a hostname to look at\n");
}

$host = $argv[1];
$test = 'conn';

$board = new XymondBoard($uri);
$result = $board->select(compact('host', 'test'), array('hostname', 'testname', 'color'));
while($row = $result->nextArray()) {
    print_r($row);
}
