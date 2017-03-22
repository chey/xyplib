<?php
namespace Xymon\Test;

use Xymon\Client\Client as XymonClient;
use Xymon\Client\Board as XymondBoard;
use Xymon\Client\HostInfo;
use Xymon\Message\Ping;
use Xymon\Client\ArrayStream;
use Xymon\Client\EntryStream;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    use TestSetupTrait;

    /**
     * This test requires a local xymond running.
     * As do alot of the other tests.
     */
    public function testClientConnection()
    {
        $entries = [];
        $c = new XymonClient($this->uri);
        $c->setTimeout(2);
        $c->send('ping');
        while (!$c->feof()) {
            $entries[] = $c->next();
        }

        $c->send(sprintf('hostinfo host=%s', gethostname()));
        while (!$c->feof()) {
            $out = $c->next();
        }

        $c->close();
    }

    public function testMagic()
    {
        $c = new XymonClient($this->uri);
        $this->assertRegExp('/^xymond \d/', (string) $c->ping()->getBody());
    }

    public function testExecute()
    {
        $client = new XymonClient($this->uri);
        $this->assertInstanceOf(ResponseInterface::class, $client->execute(new Ping));
    }

    public function testInvoke()
    {
        $client = new XymonClient($this->uri);
        $this->assertInstanceOf(ResponseInterface::class, $client(new Ping));
    }

    public function testStatic()
    {
        $response = XymonClient::hostinfo($this->uri, [gethostname() . '.conn']);
        $response = XymonClient::hostinfo($this->uri, gethostname() . '.conn');
        $this->assertInstanceOf(ResponseInterface::class, $response);

        $response = XymonClient::send($this->uri, new Ping);
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testBoard()
    {
        $response = (new XymondBoard($this->uri))->select(['host' => gethostname()], ['hostname', 'testname', 'color']);
        $this->assertInstanceOf(StreamInterface::class, $response);
        $this->assertInstanceOf(ArrayStream::class, $response);
        while(!$response->eof()) {
            $item = $response->nextArray();
        }
        $this->assertTrue(is_array($response->fetchArray()));
        $this->assertTrue(is_array($item));
        $this->assertCount(3, $item);
    }

    public function testHostInfo()
    {
        $response = (new HostInfo($this->uri))->select(['host' => gethostname()]);
        $this->assertInstanceOf(StreamInterface::class, $response);
        $this->assertInstanceOf(EntryStream::class, $response);
        while ($item = $response->nextEntry()) {
            $host = $item->getHost();
        }

        if (isset($host)) {
            $this->assertTrue(is_string($host));
        }
    }
}
