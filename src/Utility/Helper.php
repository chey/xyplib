<?php
namespace Xymon\Utility;

use Xymon\Message\MessageType;

final class Helper
{
    /**
     * Xymon output field separator. 
     */
    const FIELD_SEPARATOR = '|';

    /**
     * Default fields returned by Xymon when calling various xymond command. 
     * This doesn't change unless Xymond itself changes.
     */
    const DEFAULT_FIELDS = array(
        MessageType::Xymondboard => array(
            'hostname',
            'testname',
            'color',
            'flags',
            'lastchange',
            'logtime',
            'validtime',
            'acktime',
            'disabletime',
            'sender',
            'cookie',
            'line1'
        ),
        MessageType::Xymondlog => array(
            'hostname',
            'testname',
            'color',
            'testflags',
            'lastchange',
            'logtime',
            'validtime',
            'acktime',
            'disabletime',
            'sender',
            'cookie',
            'ackmsg',
            'dismsg',
            'client',
            'msg'
        )
    );

    /**
     * Enter a pipe delimited entry from Xymon and out comes an array.
     * 
     * @param string $line 
     * @return array
     */
    public static function parseLine($line)
    {
        return array_map('stripcslashes', preg_split('~(?<!\\\)' . preg_quote(self::FIELD_SEPARATOR, '~') . '~', rtrim($line)));
    }
}
