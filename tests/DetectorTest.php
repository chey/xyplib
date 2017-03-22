<?php
namespace Xymon\Test;

use Xymon\Message\Status;
use Xymon\Message\MessageDetector;
use Xymon\Message\MessageDetectorInterface;

class DetectorTest extends \PHPUnit_Framework_TestCase
{
    private $mock;

    public function setUp()
    {
        $this->mock = $this->getMockBuilder(MessageDetectorInterface::class)
            ->setMethods(['detect'])
            ->getMock();
    }

    public function testInterface()
    {
        $this->assertInstanceOf(MessageDetectorInterface::class, $this->mock);
    }

    public function testDetect()
    {
        $class = MessageDetector::detect('status');
        $this->assertEquals(Status::class, $class);
    }

    public function testAbnormalities()
    {
        $class = MessageDetector::detect("\nstatus");
        $this->assertEquals(Status::class, $class);
    }
}
