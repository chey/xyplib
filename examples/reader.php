<?php
/**
 * Examples for using the Reader.
 *
 * @author chey
 */
require_once 'vendor/autoload.php';

$reader = new Xymon\Config\Hosts\Reader('examples/hosts.cfg');

$entries_str = $entries_obj = array();
while (!$reader->feof()) {
    try {
        $entry = $reader->nextEntry();
    } catch (\Exception $e) {
        file_put_contents('php://stderr', 'oops!');
    }
    $entries_str[] = (string) $entry;
    $entries_obj[] = $entry;
}

print_r($entries_str);
print_r($entries_obj);
