<?php
namespace Xymon\Test;

use Xymon\Client\StatusLog;

class StatusLogTest extends \PHPUnit_Framework_TestCase
{
    use TestSetupTrait;

    public function testGet()
    {
        $client = new StatusLog($this->uri);
        $client->get(gethostname(), 'conn');
    }

    public function testCast()
    {
        $client = new StatusLog($this->uri);

        $data = <<<EOF
test-server-01.example.com|temperature|green||1472488268|1472779868|1472781668|0|0|192.168.0.10||||N|
green This is OK
Some stuff about this test with some pipes (|)
EOF;
        $this->assertEquals($client->cast($data), array(
            'test-server-01.example.com',
            'temperature',
            'green',
            '',
            '1472488268',
            '1472779868',
            '1472781668',
            '0',
            '0',
            '192.168.0.10',
            '',
            '',
            '',
            'N',
            "green This is OK\nSome stuff about this test with some pipes (|)"
        ));
    }
}
