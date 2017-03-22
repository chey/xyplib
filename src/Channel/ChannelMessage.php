<?php
namespace Xymon\Channel;

use Xymon\Channel\ProtocolType;

class ChannelMessage
{
    /**
     * @var string
     */
    private $protocol = null;

    /**
     * @var int
     */
    private $sequence = 0;

    /**
     * @var array
     */
    private $metadata = [];

    /**
     * @var MessageInterface|null
     */
    private $body = null;

    /**
     * @param string $protocol
     * @param int $sequence
     * @throws Exception\InvalidProtocolTypeException
     */
    public function __construct($protocol, $sequence)
    {
        if (!ProtocolType::isValidValue($protocol)) {
            throw new Exception\InvalidProtocolTypeException('Invalid protocol type: ' . $protocol);
        }

        $this->protocol = $protocol;
        $this->sequence = $sequence;
    }

    public function getProtocol()
    {
        return $this->protocol;
    }

    public function getSequence()
    {
        return $this->sequence;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function hasMetaItem($key)
    {
        return isset($this->metadata[$key]);
    }

    public function getMetaItem($key)
    {
        return $this->metadata[$key];
    }

    public function addMetaItem($value)
    {
        $this->metadata[] = $value;
    }

    public function setMetaItem($key, $value)
    {
        $this->metadata[$key] = $value;
    }

    public function delMetaItem($key)
    {
        unset($this->metadata[$key]);
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function __toString()
    {
        return $this->getProtocol();
    }
}
