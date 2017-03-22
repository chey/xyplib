<?php

use Xymon\Message\Status;
use Xymon\Message\Notify;
use Xymon\Message\Message;
use Xymon\Message\MessageInterface;
use Xymon\Message\Syntax\SyntaxInterface;
use Xymon\Message\Syntax\SyntaxFactory;
use Xymon\Message\Syntax\DefaultSyntax;

class CustomSyntax implements SyntaxInterface {
    public function format() {}
}
class SyntaxFoobar extends Message {protected $data = ['arg' => null];};

class SyntaxTest extends PHPUnit_Framework_TestCase
{
    public $data = [
        'hostname' => 'test-server-01.example.com',
        'testname' => 'conn'
    ];

    public function setUp()
    {
        $this->mock = $this->getMockBuilder(SyntaxInterface::class)
            ->setMethods(['format'])
            ->getMock();
    }

    public function testInterface()
    {
        $this->assertInstanceOf(SyntaxInterface::class, $this->mock);
    }

    public function testDefault()
    {
        $formatter = new DefaultSyntax(new SyntaxFoobar);
        $this->assertInstanceOf(SyntaxInterface::class, $formatter);
        $this->assertEquals('syntaxfoobar', $formatter->format());
    }

    public function testStatusSyntax()
    {
        $message = new Status($this->data);
        $this->assertEquals('status test-server-01,example,com.conn green', SyntaxFactory::create($message)->format());
    }

    public function testNotifySyntax()
    {
        $message = new Notify($this->data + array('body' => 'hi'));
        $this->assertEquals('notify test-server-01,example,com.conn hi', SyntaxFactory::create($message)->format());
    }
}
