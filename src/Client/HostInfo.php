<?php
namespace Xymon\Client;

use Xymon\Message\Hostinfo as Message;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\ResponseInterface;

/**
 * Class used for communicating with Xymond using 'hostinfo' command.
 * 
 * @author chey
 * @uses Client
 */
class HostInfo extends Client
{
    /**
     * Constructor
     *
     * @param mixed $options for http client
     */
    public function __construct($options)
    {
        if (is_string($options)) {
            $options = array('base_uri' => $options);
        }

        $handler = HandlerStack::create();
        $handler->push(Middleware::mapResponse(function (ResponseInterface $response) {
            return $response->withBody(new EntryStream($response->getBody()));
        }));

        $options += compact('handler');

        parent::__construct($options);
    }

    /**
     * Query the xymond using hostinfo.
     * 
     * @param array $criteria 
     * @return GuzzleHttp\Psr7\Response
     */
    public function select($criteria = [])
    {
        $message = new Message;
        $message->filter($criteria);
        return $this->execute($message, array('stream' => true))->getBody();
    }
}
