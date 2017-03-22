<?php
namespace Xymon\Client;

use Xymon\Utility\Helper;
use Xymon\Message\Xymondboard as Message;
use Xymon\Message\MessageType;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\ResponseInterface;

/**
 * Class used for querying/communicating with xymondboard.
 * Contains a few extra methods making it easier to work with.
 * 
 * @author chey
 * @uses Client
 */
class Board extends Client
{
    /**
     * @var Xymon\Message\Xymondboard
     */
    private $message;

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

        $this->message = new Message;

        $handler = HandlerStack::create();
        $handler->push(Middleware::mapResponse(function (ResponseInterface $response) {
            return $response->withBody(new ArrayStream($response->getBody(),
                function() {
                    return $this->message->fields();
                },
                function($line) {
                    return Helper::parseLine($line);
                }
            ));
        }));

        $options += compact('handler');

        parent::__construct($options);
    }

    /**
     * Query the xymondboard.
     * 
     * @param array $criteria 
     * @param array $fields 
     * @return GuzzleHttp\Psr7\Response
     */
    public function select($criteria = [], $fields = [])
    {
        if (!empty($criteria['fields'])) {
            $fields = array_merge($fields, (array) $criteria['fields']);
            unset($criteria['fields']);
        }
        if (empty($fields)) {
            $fields = Helper::DEFAULT_FIELDS[MessageType::Xymondboard];
        }
        $this->message->filter($criteria);
        $this->message->fields($fields);
        return $this->execute($this->message, array('stream' => true))->getBody();
    }
}
