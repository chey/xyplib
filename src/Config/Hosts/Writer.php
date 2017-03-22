<?php
namespace Xymon\Config\Hosts;

use Xymon\Config\Hosts\Entry\Entry;
use Xymon\Config\Hosts;

/**
 * Writer for Xymon hosts.cfg entries.
 *
 * @author chey
 */
class Writer {
    private $header = '%2$s%1$s%2$s Generated by Xymon PHP.%1$s%2$s%1$s';
    private $handle;

    public function __construct($uri = null) {
        if ($uri) {
            $this->open($uri);
        }
    }

    public function open($uri, $writeHeader = true) {
        $this->handle = fopen($uri, 'w');
        if ($writeHeader) {
            $this->writeHeader();
        }
        return $this->handle;
    }

    public function writeEntry(Entry $entry) {
        return fwrite($this->handle, $entry . Hosts::LINE_END);
    }

    public function writeHeader() {
        return fwrite($this->handle, sprintf($this->header, Hosts::LINE_END, Hosts::COMMENT));
    }

    public function __destruct() {
        if ($this->handle) {
            fclose($this->handle);
        }
    }
}