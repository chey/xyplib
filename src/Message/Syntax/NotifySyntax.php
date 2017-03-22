<?php
namespace Xymon\Message\Syntax;

use Xymon\Message\Notify;

class NotifySyntax implements SyntaxInterface
{
    public $message = null;

    public function __construct(Notify $message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function format()
    {
        $formatted = $this->message->command();

        if ($this->message->hostname) {
            $formatted .= ' ' . $this->message->hostname;
        }

        if ($this->message->testname) {
            $formatted .= '.' . $this->message->testname;
        }

        if ($this->message->body) {
            $formatted  .= ' ' . $this->message->body;
        }

        return $formatted;
    }
}
