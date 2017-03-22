<?php
namespace Xymon\Test;

use Xymon\Config\Hosts;

class HostsWriterTest extends \PHPUnit_Framework_TestCase
{
    public function testWriterInit() {
        $writer = new Hosts\Writer();
        $writer->open('php://stdout', false);
    }
}
