<?php
require 'vendor/autoload.php';

use Xymon\Client\StatusLog as StatusLogClient;

$uri = 'http://localhost/xymon-cgimsg/xymoncgimsg.cgi';

if (empty($argv[1])) {
    die("Please supply hostname\n");
}

if (empty($argv[2])) {
    die("Please supply testname\n");
}

$host = $argv[1];
$test = $argv[2];

$xymon = new StatusLogClient($uri);
print_r($xymon->get($host, $test));
