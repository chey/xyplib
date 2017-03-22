<?php
namespace Xymon\Test;

use Xymon\Channel\ChannelReader;

class ChannelReaderTest extends \PHPUnit_Framework_TestCase
{
    protected $_reader;

    public function setUp()
    {
        $this->_reader = new ChannelReader();
    }

    public function testGet()
    {
        $fp = fopen('php://memory', 'r+');
        fwrite($fp, "@@status#9393/test-server-01.example.com|\nhello\n@@");
        fseek($fp, 0);
        $msg = ChannelReader::get(false, $fp);

        $this->assertEquals(0, strncmp($msg, 'status', 6));
    }

    public function testParse()
    {
        $message = <<<EOF
status#3/server-01.example.com|1473703530.150720|192.168.0.10||server-01.example.com|memory|1473705330|green||green|1473460310|0||0||1465323178|server-web|test/page|0|
status server-01,example,com.memory green Mon Sep 12 14:05:29 EDT 2016 - Memory OK
   Memory              Used       Total  Percentage
&green Physical           3564M       3961M         89%
&green Actual             2854M       3961M         72%
&green Swap                138M       4095M          3%
EOF;

        $out = ChannelReader::parse($message);

        $this->assertEquals('status', $out['channel']);
        $this->assertEquals('3', $out['sequence']);
        $this->assertEquals('server-01.example.com', $out['metadata'][0]);
        $this->assertEquals('0', $out['metadata'][18]);
        $this->assertEquals(null, $out['metadata'][19]);
        $this->assertEquals('3%', substr($out['body'], -2));
    }
}
