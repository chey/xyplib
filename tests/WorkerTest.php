<?php

use Xymon\Channel\Worker;
use Xymon\Channel\ProtocolType;
use Xymon\Channel\Output\ObjectOutput;
use Xymon\Channel\Output\StringOutput;
use Xymon\Channel\ChannelMessage;

class WorkerTest extends PHPUnit_Framework_TestCase
{
    protected $_worker;

    public function setUp()
    {
        $this->_worker = new Worker();
    }

    public function testTimeout()
    {
        $this->_worker->setTimeout(1);
        $this->assertEquals(1, $this->_worker->getTimeout());
    }

    public function testStart()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->_worker->run(false);
    }

    public function testObserver()
    {
        /* See below */
        $handler = new TestHandler();
        $this->_worker->attach($handler);
        $this->_worker->notify();
        $this->_worker->detach($handler);
    }

    public function testCurrent()
    {
        $message = $this->_worker->current();
        $this->assertEquals(null, $message);
    }

    public function testNext()
    {
        $this->assertTrue(method_exists($this->_worker, 'next'));
    }

    public function testInvoke()
    {
        $this->assertTrue(is_callable($this->_worker));
    }

    public function testRunning()
    {
        $this->assertFalse($this->_worker->isRunning());
    }

    public function testOutput()
    {
        $this->_worker->setOutput(new StringOutput);

        $message = $this->_worker->output((new StringOutput))->getString();
        $this->assertTrue(strlen($message) === 0);
    }

    public function testProtocols()
    {
        $this->_worker->addProtocol(ProtocolType::Status);
        $this->_worker->addProtocol(ProtocolType::Status);
        $this->assertCount(1, $this->_worker->getProtocols());
        $this->assertTrue($this->_worker->hasProtocol(ProtocolType::Status));

        $this->_worker->delProtocol(ProtocolType::Status);
        $this->assertCount(0, $this->_worker->getProtocols());
    }

    /**
     * @expectedException Xymon\Channel\Exception\InvalidProtocolTypeException
     */
    public function testProtocalAddFail()
    {
        $this->_worker->addProtocol('chicken');
    }

    public function testWanted()
    {
        $this->assertTrue($this->_worker->isWanted());
    }

    public function tearDown()
    {
        $this->_worker->stop();
    }
}

class TestHandler implements \SplObserver
{
    public function update(\SplSubject $subject)
    {
    }
}
