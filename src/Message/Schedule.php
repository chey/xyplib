<?php
namespace Xymon\Message;

class Schedule extends Message
{
    protected $data = [
        'timestamp' => null,
        'cmd' => null
    ];

    public function setTimestamp($timestamp)
    {
        if (!(is_int($timestamp) && $timestamp > time())) {
            throw new \InvalidArgumentException('timestamp must be a valid unix time in the future');
        }

        $this->data['timestamp'] = $timestamp;
    }

    public function setCmd(MessageInterface $message)
    {
        $this->data['cmd'] = $message->syntax();
    }
    
    public function cancel($jobid)
    {
        $this->data['timestamp'] = 'cancel';
        $this->data['cmd'] = $jobid;
    }
}
