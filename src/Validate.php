<?php

namespace Xymon;

/**
 * Some basic validation methods for the tools.
 *
 * @author chey
 */
class Validate {
    /**
     * Validate an IP address.
     */
    public static function isIp($ip) {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    /**
     * http://stackoverflow.com/questions/106179/regular-expression-to-match-dns-hostname-or-ip-address
     * - Added support for underscore ~chey
     */
    public static function isHost($host) {
        $ValidHostnameRegex = "^(([a-zA-Z]|[a-zA-Z][a-zA-Z0-9\-_]*[a-zA-Z0-9])\.)*([A-Za-z]|[A-Za-z][A-Za-z0-9\-_]*[A-Za-z0-9])$";
        return (preg_match('/'.$ValidHostnameRegex.'/', $host) === 1);
    }

    /**
     * A Xymon hostname can also include '.default.' for setting default options.
     */
    public static function isXyHost($host) {
        return ($host === '.default.' || self::isHost($host));
    }
}
