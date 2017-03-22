<?php
namespace Xymon\Message;

/**
 * @author chey
 */
class MessageDetector implements MessageDetectorInterface
{
    /**
     * @var string
     */
    const CMD_DELIM = ' +/';

    /**
     * Find a class we can use based on the message.
     *
     * @param string $msg
     * @param string $fallback class|null
     * @return string class
     */
    public static function detect($msg, $fallback = null)
    {
        // Most Xymon commands end with a space but the
        // 'status' command can contain a '+' and/or a '/'
        $command = trim(strtok($msg, self::CMD_DELIM));

        if (strlen($command) < 1) {
            return $fallback;
        }

        $className = ucfirst(strtolower($command));

        if (is_subclass_of($className, MessageInterface::class)) {
            return $className;
        }

        $class = __NAMESPACE__ . '\\' . $className;

        if (is_subclass_of($class, MessageInterface::class)) {
            return $class;
        }

        return $fallback;
    }
}
