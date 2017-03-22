<?php
namespace Xymon\Test;

use Xymon\Client\ClientLog;

class ClientLogTest extends \PHPUnit_Framework_TestCase
{
    use TestSetupTrait;

    public $uri = 'http://localhost/xymon-cgimsg/xymoncgimsg.cgi';

    public function testGet()
    {
        $client = new ClientLog($this->uri);

        $client->get(gethostname());
    }

    public function testParse()
    {
        $data = <<<EOF
[abc]
123
[foo]
bar
[longline]
a
b
c
d
EOF;
        $out = ClientLog::parse($data);

        $this->assertEquals($out, array(
            'abc' => '123',
            'foo' => 'bar',
            'longline' => "a\nb\nc\nd"
        ));
    }
}
