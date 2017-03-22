<?php
namespace Xymon\Message;

use Xymon\Message\Parser\ParserInterface;
use Xymon\Utility\SimpleTokenizer;

class Client extends Message implements ParserInterface
{
    protected $data = [
        'collectorid' => null,
        'hostname' => null,
        'ostype' => null,
        'hostclass' => null,
        'body' => null
    ];

    /**
     * @return Client
     */
    public static function parse($msg)
    {
        $tokens = [
            'cmd_collid' => ' ',
            'hostname' => '.',
            'ostype' => ' ',
            'hostclass' => "\n",
            'body' => ''
        ];

        $data = SimpleTokenizer::interpret($msg, $tokens);

        $command = $data['cmd_collid'];
        $collectorid = null;
        if (strpos($command, '/')) {
            list($command, $collectorid) = explode('/', $command);
        }

        $data += compact('command', 'collectorid');

        return new self($data);
    }
}
