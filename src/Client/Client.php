<?php
namespace Xymon\Client;

use GuzzleHttp\Client as Guzzle;
use Xymon\Message\MessageInterface;

/**
 * @author chey
 */
class Client
{
    /**
     * client 
     * 
     * @var GuzzleHttp\Client
     */
    private $client;

    /**
     * Class constructor.
     * 
     * @param mixed $options 
     */
    public function __construct($options)
    {
        if (is_string($options)) {
            $options = array('base_uri' => $options);
        }
        $this->client = new Guzzle($options); 
    }

    /**
     * Magic method
     * 
     * @param string $name 
     * @param array $args 
     * @return Psr\Http\Message\ResponseInterface
     */
    public function __call($name, $args)
    {
        return $this->request($name, isset($args[0]) ? $args[0] : null);
    }

    /**
     * Magic method
     * 
     * @param string $name of xymon command
     * @param array $args uri and any other xymon params
     * @return Psr\Http\Message\ResponseInterface
     */
    public static function __callStatic($name, $args)
    {
        // The first arg needs to be a Uri when used statically.
        $Instance = new self(array_shift($args));
        if ($name === 'send' && (($message = array_shift($args)) instanceof MessageInterface)) {
            return $Instance->execute($message);
        }
        return $Instance->request($name, $args);
    }

    /**
     * Wrapper method to the Http client.
     * 
     * @param string $message
     * @param array $params
     * @param array $options Options that can be supplied to the Http client.
     * @return Psr\Http\Message\ResponseInterface
     */
    public function request($message, $params = null, $options = [])
    {
        $body = $this->prepareRequestBody($message, $params);

        $options += compact('body');
        
        return $this->client->request('POST', null, $options);
    }

    /**
     * @param MessageInterface $message
     * @param array $options
     * @return Psr\Http\Message\ResponseInterface
     */
    public function execute(MessageInterface $message, $options = [])
    {
        return $this->request($message->syntax(), null, $options);
    }

    /**
     * @param MessageInterface $message
     * @return Psr\Http\Message\ResponseInterface
     */
    public function __invoke(MessageInterface $message)
    {
        return $this->request($message->syntax());
    }

    /**
     * Format the message and parameters that will be sent to Xymond
     * 
     * @param string $message 
     * @param mixed $params 
     * @return string
     */
    private function prepareRequestBody($message, $params)
    {
        $paramsArr[] = $message;
        if (is_string($params)) {
            $paramsArr[] = $params;
        } elseif (is_array($params)) {
            $paramsArr += $params;
        }

        return implode(' ', $paramsArr);
    }
}
