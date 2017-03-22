<?php
namespace Xymon\Message;

interface MessageDetectorInterface
{
    public static function detect($msg, $fallback);
}
