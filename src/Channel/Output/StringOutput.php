<?php
namespace Xymon\Channel\Output;

class StringOutput extends OutputAbstract
{
    /**
     * @return string
     */
    public function getString()
    {
        return $this->message;
    }
}
