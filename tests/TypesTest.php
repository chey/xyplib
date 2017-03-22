<?php

use Xymon\Utility\BasicEnum;
use Xymon\Channel\ChannelType;
use Xymon\Channel\ProtocolType;
use Xymon\Message\MessageType;

class TypesTest extends PHPUnit_Framework_TestCase
{
    public function testMessageType()
    {
        $this->assertTrue(is_subclass_of(MessageType::class, BasicEnum::class));
    }

    public function testProtocolType()
    {
        $this->assertTrue(is_subclass_of(ProtocolType::class, BasicEnum::class));
    }

    public function testChannelType()
    {
        $this->assertTrue(is_subclass_of(ChannelType::class, BasicEnum::class));
    }
}
