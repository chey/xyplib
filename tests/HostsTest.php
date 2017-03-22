<?php
namespace Xymon\Test;

use Xymon\Config\Hosts\Entry;

class HostsTest extends \PHPUnit_Framework_TestCase
{
    public $ip = '127.0.0.1';
    public $host = 'localhost';

    public function testHostEntryBlank() {
        $obj = new Entry\HostEntry();
        $this->assertTrue(is_object($obj));
        $this->assertEquals(get_parent_class($obj), 'Xymon\Config\Hosts\Entry\Entry');
    }

    public function testHostEntryIp()
    {
        $this->setExpectedException('InvalidArgumentException');
        $h = new Entry\HostEntry();
        $h->setIp('999.999.999.999');
    }

    public function testHostEntryHost() {
        $this->setExpectedException('InvalidArgumentException');
        $h = new Entry\HostEntry();
        $h->setHost('999.999.999.999');
    }

    public function testHostEntry() {
        $obj = new Entry\HostEntry();

        $ip = $this->ip;
        $host = $this->host;

        $obj->setIp($ip);
        $obj->setHost($host);

        $this->assertEquals($obj->getIp(), $ip);
        $this->assertEquals($obj->getHost(), $host);

        $this->assertEquals($obj->getEntry(), "$ip $host");

        $obj->addTag('ssh');
        $this->assertEquals($obj->getEntry(), "$ip $host # ssh");

        $obj->addTag('telnet');
        $this->assertEquals($obj->getEntry(), "$ip $host # ssh telnet");

        $this->assertEquals($obj->getRaw(), "$ip $host # ssh telnet");

        $this->assertEquals($obj->getTags(), array('ssh', 'telnet'));

        $comment_tag = 'COMMENT:"This is a comment"';
        $obj->addTag($comment_tag);
        $this->assertEquals($obj->getRaw(), "$ip $host # ssh telnet $comment_tag");
    }

    public function testHostEntryUnderscore() {
        $obj = new Entry\HostEntry();

        $ip = '192.168.0.1';
        $host = 'host_with_underscore';

        $obj->setIp($ip);
        $obj->setHost($host);

        $this->assertEquals($obj->getIp(), $ip);
        $this->assertEquals($obj->getHost(), $host);
    }

    public function testHostEntryFromString() {
        $ip = $this->ip;
        $host = $this->host;
        $tags = 'ssh telnet COMMENT:"This is a comment"';
        $tag_new = "http://$host";

        $tags_arr = explode(' ', $tags, 3);

        $string = "$ip $host # $tags";

        $obj = new Entry\HostEntry($string);

        $this->assertEquals($obj->getIp(), $ip);
        $this->assertEquals($obj->getHost(), $host);

        $this->assertEquals($obj->getTags(), $tags_arr);

        $this->assertEquals($obj->getEntry(), $string);

        $obj->addTag($tag_new);

        $this->assertEquals($obj->getEntry(), $string . " $tag_new");

        $tags_arr[] = $tag_new;
        $this->assertEquals($obj->getTags(), $tags_arr);
    }

    public function testHostEntryFromArray() {
        $ip = $this->ip;
        $host = $this->host;
        $entry = "$ip $host";

        $arr = array($ip, $host);

        $obj = new Entry\HostEntry($arr);

        $this->assertEquals($obj->getEntry(), $entry);
    }

    public function testTagQuote()
    {
        $ip = $this->ip;
        $host = $this->host;
        $entry = "$ip $host";
        $obj = new Entry\HostEntry($entry);
        $obj->addTag('DESCR:hosttype:My host in NYC');

        $this->assertEquals($obj->getTags(), array('DESCR:"hosttype:My host in NYC"'));
    }

    public function testOrder()
    {
        $arr = array($this->host, $this->ip);
        $obj = new Entry\HostEntry();
        $obj->setHost(array_shift($arr));
        $obj->setIp(array_shift($arr));
        $this->assertEquals($obj->getEntry(), "$this->ip $this->host");
    }
}
