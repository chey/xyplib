<?php
namespace Xymon\Test;

use Xymon\Config\Hosts;

class HostsReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testReader01() {
        $rendered_version = 'tests/test_hosts_reader_01_rendered.cfg';
        $count = 28;
        $entries = array();
        $reader = new Hosts\Reader('tests/test_hosts_reader_01.cfg');
        while (!$reader->feof()) {
            $entries[] = $reader->nextEntry();
        }

        $this->assertTrue(count($entries) === $count);
        $this->assertEquals(file_get_contents($rendered_version), implode("\n", $entries));
    }
}
