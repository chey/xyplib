<?php
namespace Xymon\Message;

class Modify extends Message
{
    use Traits\Hostname;
    use Traits\Testname;

    const DEFINITION = [
        'command' => ' ',
        'hostname' => '.',
        'testname' => ' ',
        'color' => ' ',
        'source' => ' ',
        'cause' => ''
    ];
}
