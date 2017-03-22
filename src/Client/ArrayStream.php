<?php
namespace Xymon\Client;

use Psr\Http\Message\StreamInterface;

/**
 * @author chey
 */
class ArrayStream extends ReadlineStream
{
    /**
     * @var callable
     */
    private $keyCallback;

    /**
     * @var callable
     */
    private $valCallback;

    /**
     * Constructor
     *
     * @param StreamInterface $stream
     * @param callable $keyCallback
     * @param callable $valCallback
     */
    public function __construct(StreamInterface $stream, callable $keyCallback, callable $valCallback)
    {
        $this->keyCallback = $keyCallback;
        $this->valCallback = $valCallback;
        parent::__construct($stream);
    }

    /**
     * @return array associative array of values
     */
    public function nextArray()
    {
        $line = $this->readline();

        if (!$line) {
            return false;
        }

        return array_combine(
            call_user_func($this->keyCallback),
            call_user_func_array($this->valCallback, [$line])
        );
    }

    /**
     * @return array of entire response
     */
    public function fetchArray()
    {
        $items = [];

        while ($item = $this->nextArray()) {
            $items[] = $item;
        }

        return $items;
    }
}
