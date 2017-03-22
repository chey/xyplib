<?php
namespace Xymon\Test;

use Xymon\Message\MessageInterface;
use Xymon\Message\Message;
use Xymon\Message\Status;
use Xymon\Message\Notify;
use Xymon\Message\Parser\ParserInterface;
use Xymon\Message\Parser\StatusParser;
use Xymon\Message\Parser\NotifyParser;
use Xymon\Message\Parser\ParserFactory;

class TestFoobar extends Message {}
class TestFoobarParser implements ParserInterface {
    public static function parse($msg) {}
}

class ParserTest extends \PHPUnit_Framework_TestCase
{
    public $rawMsg = "status+30/group:sysops test-server-01,example,com.conn green A OK\n<b>this and that</b>\nFoo";

    public $mock;

    public function setUp()
    {
        $this->mock = $this->getMockBuilder(ParserInterface::class)
            ->setMethods(['parse'])
            ->getMock();
    }

    public function testInterface()
    {
        $this->assertInstanceOf(ParserInterface::class, $this->mock);
    }

    public function testFactory()
    {
        $parserObj = ParserFactory::create(get_class($this->mock));
        $this->assertInstanceOf(ParserInterface::class, $parserObj);

        $parserObj = ParserFactory::create(TestFoobarParser::class);
        $this->assertInstanceOf(TestFoobarParser::class, $parserObj);

        $parserObj = ParserFactory::create(new TestFoobar);
        $this->assertInstanceOf(TestFoobarParser::class, $parserObj);

        $parserObj = ParserFactory::create("doesnotexist", TestFoobarParser::class);
        $this->assertInstanceOf(TestFoobarParser::class, $parserObj);
    }

    public function testStatusParser()
    {
        $message = StatusParser::parse($this->rawMsg);
        $this->assertInstanceOf(Status::class, $message);
        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertEquals('status', $message->command());
        $this->assertEquals(30, $message->lifetime);
        $this->assertEquals('sysops', $message->group);
        $this->assertEquals('test-server-01,example,com', $message->hostname);
        $this->assertEquals('conn', $message->testname);
        $this->assertEquals('green', $message->color);
        $this->assertEquals('A OK', $message->summary);
        $this->assertEquals("<b>this and that</b>\nFoo", $message->body);
    }

    public function testNotifyParser()
    {
        $rawMsg = "notify test-server-01,example,com.conn foo\nbar";
        $message = NotifyParser::parse($rawMsg);
        $this->assertInstanceOf(Notify::class, $message);
        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertEquals('notify', $message->command());
        $this->assertEquals('test-server-01,example,com', $message->hostname);
        $this->assertEquals('conn', $message->testname);
        $this->assertEquals("foo\nbar", $message->body);
    }
}
