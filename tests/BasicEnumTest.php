<?php
namespace Xymon\Test;

use Xymon\Utility\BasicEnum;

class MyTestEnum extends BasicEnum {
    const MyKey = 1;
}

class BasicEnumTest extends \PHPUnit_Framework_TestCase
{
    public function testEnum()
    {
        $this->assertEquals(1, MyTestEnum::MyKey);
        $this->assertTrue(MyTestEnum::isValidName('MyKey'));
        $this->assertTrue(MyTestEnum::isValidName('mykey'));
        $this->assertFalse(MyTestEnum::isValidName('mykey', true));
        $this->assertTrue(MyTestEnum::isValidValue(1));
        $this->assertEquals(array('MyKey' => 1), MyTestEnum::getConstList());
    }
}
