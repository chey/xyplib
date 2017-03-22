<?php
namespace Xymon;

if (!function_exists('milliseconds')) {
    /**
     * Return time in milliseconds.
     */
    function milliseconds() {
        return round(microtime(true) * 1000);
    }
}

if (!function_exists('get_class_name')) {
    function get_class_name($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        if (strpos($class, '\\')) {
            $class = substr(strrchr($class, '\\'), 1);
        }

        return $class;
    }
}
