<?php
namespace Xymon\Message\Syntax;

use Xymon\Message\Client;

class ClientSyntax implements SyntaxInterface
{
    /**
     * @var Client
     */
    public $message = null;

    /**
     * @param Client $message
     */
    public function __construct(Client $message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function format()
    {
        $formatted = $this->message->command();

        if ($this->message->collectorid) {
            $formatted .= '/' . $this->message->collectorid;
        }

        if ($this->message->hostname) {
            $formatted .= ' ' . $this->message->hostname;
        }

        if ($this->message->ostype) {
            $formatted .= '.' . $this->message->ostype;
        }

        if ($this->message->hostclass) {
            $formatted .= ' ' . $this->message->hostclass;
        }

        if ($this->message->body) {
            $formatted .= "\n" . $this->message->body;
        }

        return $formatted;
    }
}
