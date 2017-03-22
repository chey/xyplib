<?php
namespace Xymon\Test;

use Xymon\Message\MessageInterface;
use Xymon\Message\Message;
use Xymon\Message\UnknownMessage;
use Xymon\Message\Factory;

use Xymon\Message\Status;
use Xymon\Message\Data;
use Xymon\Message\Notify;
use Xymon\Message\Client;
use Xymon\Message\Disable;
use Xymon\Message\Enable;
use Xymon\Message\Query;
use Xymon\Message\Config;
use Xymon\Message\Download;
use Xymon\Message\Notes;
use Xymon\Message\Drop;
use Xymon\Message\Rename;
use Xymon\Message\Xymondack;
use Xymon\Message\Xymondlog;
use Xymon\Message\Xymondxlog;
use Xymon\Message\Xymondboard;
use Xymon\Message\Xymondxboard;
use Xymon\Message\Hostinfo;
use Xymon\Message\Clientlog;
use Xymon\Message\Ping;
use Xymon\Message\Pullclient;
use Xymon\Message\Ghostlist;
use Xymon\Message\Schedule;
use Xymon\Message\Usermsg;
use Xymon\Message\Modify;

class Foobar extends Message {};

class MessageTest extends \PHPUnit_Framework_TestCase
{

    public $rawMsg = "status+30/group:sysops test-server-01,example,com.conn green A OK\n<b>this and that</b>\nFoo";

    public function testInterface()
    {
        $mock = $this->getMockBuilder(MessageInterface::class)
            ->setMethods(['__toString', 'syntax', 'data', 'command', 'getIterator'])
            ->getMock();

        $this->assertInstanceOf(MessageInterface::class, $mock);
        $this->assertEquals('', $mock->__toString());
    }

    public function testFactory()
    {
        $this->assertInstanceOf(Status::class, Factory::create(Status::class));
        $this->assertInstanceOf(Foobar::class, Factory::create(Foobar::class));

        $message = Factory::create($this->rawMsg);
        $this->assertInstanceOf(\IteratorAggregate::class, $message);
        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertInstanceOf(Status::class, $message);

        $this->assertInstanceOf(UnknownMessage::class, Factory::create('blah blah blah', UnknownMessage::class));
    }

    public function testStatusMessage()
    {
        $message = new Status(['hostname' => 'test-server-01.example.com', 'testname' => 'conn']);
        $message->setLifetime(30);
        $message->setGroup('sysops');
        $message->setSummary("\nA\nOK\n\r");
        $message->setBody("<b>this and that</b>\nFoo");
        $this->assertEquals('status', $message->command());
        $this->assertEquals('sysops', $message->getGroup());
        $this->assertEquals("<b>this and that</b>\nFoo", $message->getBody());
        $this->assertEquals('test-server-01,example,com', $message->getHostname());
        $this->assertEquals('test-server-01,example,com', $message->hostname);

        $this->assertEquals($this->rawMsg, $message->syntax());

        $message->testname = 'ssh';
        $this->assertEquals('ssh', $message->getTestname());

        // Make sure we can set certain values to null if we want
        $message->lifetime = null;
        $message->group = null;
    }

    public function testNotifyMessage()
    {
        $notify = new Notify();
        $notify = Factory::create('notify');
        $notify = Factory::create(Notify::class);
        $notify = Factory::create(new Notify);
        $this->assertInstanceOf(MessageInterface::class, $notify);
        $this->assertEquals('notify', (string) $notify);
        $this->assertEquals('notify', $notify->syntax());

        $notifyMsg = 'notify test-server-01,example,com.conn foobar';

        $notify = Factory::create($notifyMsg);
        $this->assertEquals($notifyMsg, $notify->syntax());
    }

    public function testDataMessage()
    {
        $dataMsg = "data test-server-01,example,com.conn\nstuff";
        $test = new Data();
        $test = Factory::create('data');
        $test = Factory::create(Data::class);
        $test = Factory::create(new Data);
        $test = Factory::create($dataMsg);
        $this->assertInstanceOf(MessageInterface::class, $test);

        $this->assertEquals('data', $test->command());
        $this->assertEquals('test-server-01,example,com', $test->hostname);
        $this->assertEquals('conn', $test->dataname);
        $this->assertEquals('stuff', $test->body);
        $this->assertEquals(['hostname' => 'test-server-01,example,com', 'dataname' => 'conn', 'body' => 'stuff'], $test->data());

        $this->assertEquals($dataMsg, $test->syntax());
    }

    public function testClientMessage()
    {
        $clientMsg = "client/extra test-server-01,example,com.linux linux\nall the fun stuff";
        $clientMsg2 = "client test-server-01,example,com.linux linux\nall the fun stuff";
        $client = new Client();
        $client = Factory::create('client');
        $client = Factory::create(Client::class);
        $client = Factory::create(new Client);
        $client = Factory::create($clientMsg);
        $this->assertInstanceOf(MessageInterface::class, $client);

        $this->assertEquals('client', $client->command());
        $this->assertEquals('extra', $client->collectorid);
        $this->assertEquals('test-server-01,example,com', $client->hostname);
        $this->assertEquals('linux', $client->ostype);
        $this->assertEquals('linux', $client->hostclass);
        $this->assertEquals('all the fun stuff', $client->body);

        $this->assertEquals($clientMsg, $client->syntax());

        $client2 = Factory::create($clientMsg2);
        $this->assertEquals(null, $client2->collectorid);
    }

    public function testQueryMessage()
    {
        $msg = new Query;
        $this->assertEquals('query', $msg);
    }

    public function testDrop()
    {
        $msg = new Drop(['hostname' => 'test-server-01.example.com']);
        $this->assertEquals('drop test-server-01.example.com', $msg->syntax());

        $msg = new Drop(['hostname' => 'test-server-01.example.com', 'testname' => 'conn']);
        $this->assertEquals('drop test-server-01.example.com conn', $msg->syntax());
    }

    public function testRename()
    {
        $rename = new Rename;

        $rename = Rename::host('test-server-01,example,com', 'test-server-02,example,com');
        $this->assertEquals('rename test-server-01,example,com test-server-02,example,com', $rename->syntax());

        $rename = Rename::test('test-server-01,example,com', 'customtest1', 'customtest2');
        $this->assertEquals('rename test-server-01,example,com customtest1 customtest2', $rename->syntax());
    }

    public function testOtherMessages()
    {
        $msg = new Config;
        $msg = new Download;
        $msg = new Notes;
        $msg = new Enable;
        $msg = new Xymondlog;
        $msg = new Xymondxlog;
        $msg = new Ghostlist;
        $msg = new Pullclient;
        $msg = new Usermsg;
    }

    public function testDisable()
    {
        $message = Disable::parse('disable test-server-01,example,com');
    }

    public function testXymondboard()
    {
        $board = new Xymondboard;
        $this->assertEquals('xymondboard', $board->syntax());
        $board->filter(['page' => 'testpage']);
        $board->filter('page', 'testpage');
        $this->assertEquals('xymondboard page=testpage', $board->syntax());
        $board->fields(['hostname']);
        $board->fields('hostname');
        $board->fields('hostname', 'testname');
        $this->assertEquals('xymondboard page=testpage fields=hostname,testname', $board->syntax());

        $board->filter('test', 'conn');
        $this->assertEquals('xymondboard page=testpage test=conn fields=hostname,testname', $board->syntax());

        $board->filter('msg', 'Data Center');
        $this->assertEquals('xymondboard page=testpage test=conn msg="Data Center" fields=hostname,testname', $board->syntax());

        // Test the operators
        $dayago = strtotime('-1 day');
        $board->filter('lastchange', ">$dayago");
        $this->assertEquals("xymondboard page=testpage test=conn msg=\"Data Center\" lastchange>$dayago fields=hostname,testname", $board->syntax());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testXymondxboard()
    {
        $xboard = new Xymondxboard;
        $xboard->fields();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testHostinfo()
    {
        $info = new Hostinfo;
        $info->fields();
    }

    /**
     * NOTICE!!: Seems the whole comma (,) in place of the periods (.) for the xymon commands
     * does not apply to the 'clientlog' command.
     */
    public function testClientlog()
    {
        $log = new Clientlog;
        $this->assertEquals('clientlog', $log->syntax());

        $log->setHostname('test-server-01.example.com');
        $this->assertEquals('clientlog test-server-01.example.com', $log->syntax());

        $log->section('date');
        $this->assertEquals('clientlog test-server-01.example.com section=date', $log->syntax());

        $log->section('uname');
        $this->assertEquals('clientlog test-server-01.example.com section=date,uname', $log->syntax());
    }


    public function testSchedule()
    {
        $schedule = new Schedule;
        $this->assertEquals('schedule', $schedule->syntax());

        $in5 = time() + 300;
        $schedule->setTimestamp($in5);
        $schedule->setCmd(new Ping);

        $this->assertEquals("schedule $in5 ping", $schedule->syntax());

        $schedule->cancel(1);
        $this->assertEquals('schedule cancel 1', $schedule->syntax());
    }

    public function testModify()
    {
        $data = [
            'hostname' => 'test-server-01,example,com',
            'testname' => 'conn',
            'color' => 'purple',
            'source' => 'app',
            'cause' => 'because i said so'
        ];
        $modify = new Modify($data);
        $this->assertEquals('modify test-server-01,example,com.conn purple app because i said so', $modify->syntax());

        foreach ($data as $k => $v) {
            $this->assertEquals($v, $modify->$k);
        }
    }

    public function testXymondack()
    {
        $str = 'xymondack 534143643 1m we know';
        $arr = [
            'cookie' => '534143643',
            'duration' => '1m',
            'body' => 'we know'
        ];
        $ack = new Xymondack($arr);
        $this->assertInstanceOf(MessageInterface::class, $ack);
        $this->assertEquals($str, $ack->syntax());
    }

    public function testStatusAbnormalities()
    {
        // Upper case color should be normalized to lowercase
        $status = new Status(['hostname' => 'test-server-01.example.com', 'testname' => 'conn', 'color' => 'RED']);

        // We could see extra whitespace in the first line of a status message
        $status = Factory::create('status test-server-01.example.com.conn  yellow A OK');

        // We may or may not have a summary
        $status = Factory::create('status test-server-01.example.com.conn yellow');
    }
}
