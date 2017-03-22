<?php
namespace Xymon\Message\Syntax;

use Xymon\Message\Status;

class StatusSyntax implements SyntaxInterface
{
    public $message = null;

    public function __construct(Status $message)
    {
        $this->message = $message;
    }

    public function format()
    {
        $out = $this->message->command();

        if (is_numeric($this->message->lifetime)) {
            $out .= '+' . $this->message->lifetime;
        }

        if ($this->message->group) {
            $out .= '/group:' . $this->message->group;
        }

        if ($this->message->hostname) {
            $out .= ' ' . str_replace('.', ',', $this->message->hostname);
        }

        if ($this->message->testname) {
            $out .= '.' . $this->message->testname;
        }

        if ($this->message->color) {
            $out .= ' ' . $this->message->color;
        }

        if ($this->message->summary) {
            $out .= ' ' . $this->message->summary;
        }

        if ($this->message->body) {
            $out .= "\n" . $this->message->body;
        }

        return $out;
    }
}
