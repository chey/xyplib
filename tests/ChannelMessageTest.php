<?php
namespace Xymon\Test;

use Xymon\Channel\ChannelMessage;

class ChannelMessageTest extends \PHPUnit_Framework_TestCase
{
    public function testAll()
    {
        $message = new ChannelMessage('status', '333');

        $this->assertEquals('status', $message->getProtocol());
        $this->assertEquals('status', (string) $message);
        $this->assertEquals('333', $message->getSequence());

        $message->addMetaItem('test-server-01.example.com');

        $this->assertEquals('test-server-01.example.com', $message->getMetaItem(0));

        $message->setMetaItem(0, 'test-server-02.example.com');

        $this->assertEquals('test-server-02.example.com', $message->getMetaItem(0));

        $this->assertEquals(['test-server-02.example.com'], $message->getMetaData());

        $this->assertTrue($message->hasMetaItem(0));
        $this->assertFalse($message->hasMetaItem(100));

        $message->delMetaItem(0);
        $this->assertEquals([], $message->getMetaData());

        $message->setBody(null);
        $this->assertEquals(null, $message->getBody());
    }
}
