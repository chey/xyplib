<?php
namespace Xymon\Message;

class Enable extends Message
{
    use Traits\Hostname;
    use Traits\Testname;

    const DEFINITION = [
        'command' => ' ',
        'hostname' => '.',
        'testname' => ''
    ];
}
