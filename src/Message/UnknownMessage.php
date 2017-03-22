<?php
namespace Xymon\Message;

class UnknownMessage extends Message
{
    protected $data = [
        'unknown' => null
    ];

    public static function create($command, $data = array())
    {
        $instance = new self($data);
        $instance->command = $command;
        return $instance;
    }
}
