<?php
namespace Xymon\Message\Syntax;

use Xymon\Message\MessageInterface;

class SyntaxFactory
{
    /**
     * @param mixed $classOrObject
     * @return SyntaxInterface
     * @throws \InvalidArgumentException
     */
    public static function create($classOrObject, $fallback = null)
    {
        if (is_subclass_of($classOrObject, MessageInterface::class)) {
            if (is_object($classOrObject)) {
                $name = get_class($classOrObject);
            } else {
                $name = $classOrObject;
            }

            $name = $name . 'Syntax';

            if (class_exists($name)) {
                $syntaxClass = $name;
            } else {
                $syntaxClass = __NAMESPACE__ . '\\' . \Xymon\get_class_name($name);
            }
        } else {
            $syntaxClass = $classOrObject;
        }

        if ($fallback !== null && !is_subclass_of($syntaxClass, SyntaxInterface::class)) {
            $syntaxClass = $fallback;
        }

        if (is_subclass_of($syntaxClass, SyntaxInterface::class)) {
            if (is_object($classOrObject)) {
                return new $syntaxClass($classOrObject);
            } else {
                return new $syntaxClass;
            }
        }

        throw new \InvalidArgumentException(sprintf('Unable to find a syntax for %s', $classOrObject));
    }
}
