<?php
namespace Xymon\Test;

use Xymon\Utility\SimpleTokenizer;

class SimpleTokenizerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterpret()
    {
        $string = 'hello world lastword';

        // a string with an empty token indicates 'rest of string'
        $tokens = ['firstword' => ' ', 'secondword' => ' ', ''];

        $result = SimpleTokenizer::interpret($string, $tokens);

        $this->assertEquals('hello', $result['firstword']);
        $this->assertEquals('world', $result['secondword']);
        $this->assertCount(2, $result);
    }
}
