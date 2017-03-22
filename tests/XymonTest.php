<?php

use Xymon\Xymon;

class XymonTest extends PHPUnit_Framework_TestCase
{
    public function testVersion()
    {
        $x = new Xymon();
        $this->assertEquals($x::VERSION, '0.1');
    }
}
