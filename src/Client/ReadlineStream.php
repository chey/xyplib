<?php
namespace Xymon\Client;

use GuzzleHttp\Psr7\StreamWrapper;
use GuzzleHttp\Psr7\NoSeekStream;
use Psr\Http\Message\StreamInterface;

/**
 * Implements readline() method for the Response object.
 *
 * @author chey
 */
class ReadlineStream extends NoSeekStream
{
    /**
     * @var resource
     */
    protected $resource;

    /**
     * @param Psr\Http\Message\StreamInterface $stream
     */
    public function __construct(StreamInterface $stream)
    {
        parent::__construct($stream);
        $this->resource = StreamWrapper::getResource($this->stream);
    }

    /**
     * @return string of the next line
     */
    public function readline()
    {
        // fgets is faster than GuzzleHttp\Psr7\readline
        return fgets($this->resource);
    }
}
