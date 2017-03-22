<?php
/**
 * Example hosts.cfg writer for Xymon
 *
 * @author chey
 */
require_once 'vendor/autoload.php';
use Xymon\Config\Hosts\Entry\HostEntry;
use Xymon\Config\Hosts\Writer;

$entries = array();
$entry = new HostEntry('127.0.0.1', 'localhost');
$entry->addTag('ssh');
$entry->addTag('telnet');
$entries[] = $entry;
$entries[] = new HostEntry('8.8.8.8', 'google-public-dns-a.google.com');
$writer = new Writer('php://stdout');
foreach ($entries as $e) {
    $writer->writeEntry($e);
}
