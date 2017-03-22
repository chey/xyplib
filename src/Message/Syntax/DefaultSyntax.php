<?php
namespace Xymon\Message\Syntax;

use Xymon\Message\MessageInterface;

class DefaultSyntax implements SyntaxInterface
{
    /**
     * @var MessageInterface
     */
    private $message = null;

    /**
     * @param MessageInterface $message
     */
    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function format()
    {
        $class = get_class($this->message);

        if (defined("$class::DEFINITION")) {
            $ret = [];
            $lasts = null;
            foreach ($class::DEFINITION as $k => $s) {
                if (isset($this->message->$k) && strlen($this->message->$k) > 0) {
                    $ret[] = $this->message->$k;
                    $ret[] = $lasts = $s;
                }
            }

            // Make sure we don't end with a separator
            if (!empty($ret)) {
                if ($ret[count($ret)-1] === $lasts) {
                    array_pop($ret);
                }
            }

            // Add the space after the command
            if (!empty($ret)) {
                array_unshift($ret, ' ');
            }

            array_unshift($ret, $this->message->command());

            return implode('', $ret);
        }

        $data = array_filter($this->message->data(), 'strlen');
        array_unshift($data, $this->message->command());
        return implode(' ', $data);
    }
}
