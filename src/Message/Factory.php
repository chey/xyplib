<?php
namespace Xymon\Message;

use Xymon\Message\Parser\ParserFactory;

class Factory
{
    /**
     * @param string $msgOrClass
     * @param string $fallback class to use for unknown messages
     * @return MessageInterface|null
     * @throws \InvalidArgumentException
     */
    public static function create($msgOrClass, $fallback = null)
    {
        if (is_subclass_of($msgOrClass, MessageInterface::class)) {
            return new $msgOrClass;
        }

        // At this point we are looking at a msg string and not a class.
        // So we will try to figure out the class, and load the correct parser.
        if ($class = MessageDetector::detect($msgOrClass, $fallback)) {
            return ParserFactory::create($class)->parse($msgOrClass);
        }

        throw new \InvalidArgumentException(sprintf(
            "'%s' is not a subclass of %s",
            strtok($msgOrClass, MessageDetector::CMD_DELIM), MessageInterface::class
        ));
    }
}
