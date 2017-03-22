<?php
namespace Xymon\Message\Parser;

use Xymon\Message\MessageInterface;

class ParserFactory
{
    /**
     * @param mixed $classOrObject
     * @param string $fallback class to use when parser cannot be found
     * @return ParserInterface
     * @throws \InvalidArgumentException
     */
    public static function create($classOrObject, $fallback = null)
    {
        if (is_subclass_of($classOrObject, ParserInterface::class)) {
            return new $classOrObject;
        }

        if (is_subclass_of($classOrObject, MessageInterface::class)) {
            if (is_object($classOrObject)) {
                $name = get_class($classOrObject);
            } else {
                $name = $classOrObject;
            }

            $name = $name . 'Parser';

            if (class_exists($name)) {
                $parserClass = $name;
            } else {
                $parserClass = __NAMESPACE__ . '\\' . \Xymon\get_class_name($name);
            }
        } else {
            $parserClass = $classOrObject;
        }

        if ($fallback !== null && !is_subclass_of($parserClass, ParserInterface::class)) {
            $parserClass = $fallback;
        }

        if (is_subclass_of($parserClass, ParserInterface::class)) {
            if (is_object($classOrObject)) {
                return new $parserClass($classOrObject);
            } else {
                return new $parserClass;
            }
        }

        throw new \InvalidArgumentException(sprintf('Unable to find a parser for %s', $classOrObject));
    }
}
