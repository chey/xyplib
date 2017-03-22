<?php
namespace Xymon\Test;

class FunctionsTest extends \PHPUnit_Framework_TestCase
{
    public function testMilliseconds()
    {
        $m = \Xymon\milliseconds();
    }

    public function getGetClassName()
    {
        $this->assertEquals('FunctionsTest', \Xymon\get_class_name($this));
        $this->assertEquals('FunctionsTest', \Xymon\get_class_name(static::class));
    }
}
