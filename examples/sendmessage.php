<?php
/**
 * @author chey
 */
require 'vendor/autoload.php';

use Xymon\Client\Client;
use Xymon\Message\Message;

class Ping extends Message {};

$uri = 'http://localhost/xymon-cgimsg/xymoncgimsg.cgi';

$client = new Client($uri);

echo $client->execute((new Ping))->getBody();
