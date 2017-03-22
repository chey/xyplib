<?php
namespace Xymon\Message;

class Drop extends Message
{
    //use Traits\Hostname; // TODO
    use Traits\Testname;

    const DEFINITION = [
        'command' => ' ',
        'hostname' => ' ',
        'testname' => ''
    ];
}
