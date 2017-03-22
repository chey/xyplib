<?php
namespace Xymon\Test;

use Xymon\Channel\Worker;
use Xymon\Channel\ChannelMessage;
use Xymon\Channel\Output\OutputAbstract;
use Xymon\Channel\Output\ObjectOutput;
use Xymon\Channel\Output\StringOutput;
use Xymon\Message\UnknownMessage;

class OutputInterfaceTest extends \PHPUnit_Framework_TestCase
{
    public function testOutputInterface()
    {
        $mock = $this->getMockBuilder(OutputInterface::class)
            ->setMethods(['set'])
            ->getMock();

        $this->assertInstanceOf(OutputInterface::class, $mock);
        $this->assertNull($mock->set());
    }

    public function testAbstract()
    {
        $this->assertInstanceOf(OutputAbstract::class, new StringOutput);
        $this->assertInstanceOf(OutputAbstract::class, new ObjectOutput);
    }

    public function testObjectOutput()
    {
        $this->assertInstanceOf(ObjectOutput::class, new ObjectOutput);

        $output = new ObjectOutput();
        $output->set("status#9/blah");

        $this->assertInstanceOf(ChannelMessage::class, $output->getObject());

        $mock = $this->getMockBuilder(UnknownMessage::class)->getMock();

        // Test the fallback
        $this->assertInstanceOf(ChannelMessage::class, $output->getObject($mock));
    }

    public function testStringOutput()
    {
        $this->assertInstanceOf(StringOutput::class, new StringOutput);

        $output = new StringOutput();

        $this->assertEquals(null, $output->getString());
    }
}
