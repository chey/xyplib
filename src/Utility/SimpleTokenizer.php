<?php
namespace Xymon\Utility;

/**
 * Simple way to parse a string with strtok().
 *
 * We put our tokens in an associative array where the keys
 * are the variables we want.
 *
 * Numeric keys will be found but not assigned to a variable.
 *
 * @author chey
 */
final class SimpleTokenizer
{
    /**
     * @param string $string to be parsed
     * @param array $tokens associative array where the key is the variable and the value is the token
     */
    final public static function interpret($string, $tokens)
    {
        $values = [];
        $start = each($tokens);

        if ($start !== false && false !== ($result = strtok($string, $start['value']))) {
            if (!is_numeric($start['key'])) {
                $values[$start['key']] = $result;
            }

            while (list($key, $token) = each($tokens)) {
                if (false === ($result = strtok($token))) {
                    break;
                }

                if (!is_numeric($key)) {
                    $values[$key] = $result;
                }
            }
        }

        return $values;
    }
}
