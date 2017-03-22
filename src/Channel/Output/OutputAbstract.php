<?php
namespace Xymon\Channel\Output;

abstract class OutputAbstract implements OutputInterface
{
    /**
     * @var string
     */
    protected $message = null;

    /**
     * @param string $message
     */
    public function set($message)
    {
        $this->message = $message;
    }

}
