<?php
namespace Xymon\Message;

class Notify extends Message
{
    use Traits\Hostname;
    use Traits\Testname;

    /**
     * Definition of a Notify message.
     */
    const DEFINITION = [
        'command' => ' ',
        'hostname' => '.',
        'testname' => ' ',
        'body' => ''
    ];
}
