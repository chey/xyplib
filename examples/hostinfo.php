<?php
/**
 * Working example of the Xymond hostinfo command.
 *
 * @author chey
 */

require 'vendor/autoload.php';

use Xymon\Client\HostInfo;

$uri = 'http://localhost/xymon-cgimsg/xymoncgimsg.cgi';

$hi = new HostInfo($uri);

if (empty($argv[1])) {
    die("Supply a hostname\n");
}

$result = $hi->select(array('host' => $argv[1]));
while ($entry = $result->nextEntry()) {
    echo $entry->getIp(), PHP_EOL;
}
