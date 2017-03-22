<?php
namespace Xymon\Config;

/**
 * This will be the basis for parsing the Xymon config files.
 * e.g xymonserver.cfg
 *
 * @author chey
 */
class Generic
{
    public function __construct($file)
    {
        if (!is_readable($file)) {
            throw new \InvalidArgumentException("Unable to open $file");
        }
    }
}
