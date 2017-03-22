<?php
namespace Xymon\Test;

use Xymon\Config\Hosts\Reader;
use Xymon\Config\Hosts\Entry\CommentEntry;
use Xymon\Config\Hosts\Entry\HostEntry;
use Xymon\Config\Hosts\Entry\PageEntry;
use Xymon\Config\Hosts\Entry\GroupEntry;
use Xymon\Config\Hosts\Entry\IncludeEntry;
use Xymon\Config\Hosts\Entry\DirectoryEntry;

class HostsMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testMapper01() {
        $count = 28;
        $entries = array();
        $reader = new Reader('tests/test_hosts_reader_01.cfg');
        while (!$reader->feof()) {
            $entries[] = $reader->nextEntry();
        }

        $this->assertInstanceOf(CommentEntry::class, $entries[0]);
        $this->assertInstanceOf(HostEntry::class, $entries[4]);
        $this->assertInstanceOf(PageEntry::class, $entries[12]);
        $this->assertInstanceOf(GroupEntry::class, $entries[15]);
        $this->assertInstanceOf(IncludeEntry::class, $entries[19]);
        $this->assertInstanceOf(DirectoryEntry::class, $entries[23]);
        $this->assertTrue($entries[$count-1] === null);
    }
}
