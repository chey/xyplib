<?php
/**
 * Working example of the Xymon client.
 *
 * @author chey
 */

require 'vendor/autoload.php';

use Xymon\Client\Client as XymonClient;

$uri = 'http://localhost/xymon-cgimsg/xymoncgimsg.cgi';

if (empty($argv[1])) {
    die("Please supply xymon command\n");
}

$command = $argv[1];

$xymon = new XymonClient($uri);
echo $xymon->request($command)->getBody();
