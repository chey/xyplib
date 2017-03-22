<?php

namespace Xymon\Config\Hosts;

use Xymon\Config\Hosts;

/**
 * Used to parse Xymon hosts.cfg files.
 *
 * @author chey
 */
class Reader {
    private $uri;
    private $handle;

    /**
     * Constructor
     */
    public function __construct($uri = null) {
        if ($uri) {
            $this->open($uri);
        }
    }

    /**
     * Open file for reading.
     */
    public function open($uri) {
        $this->handle = fopen($uri, 'r');
        if (!$this->handle) {
            throw new \InvalidArgumentException("Unable to open $uri");
        }
        $this->uri = $uri;
    }

    /**
     * Return the path to the file being used.
     */
    public function getUri() {
        return $this->uri;
    }

    /**
     * Check for the end of the file.
     */
    public function feof() {
        return feof($this->handle);
    }

    /**
     * Move to the next line in the file.
     */
    public function next() {
        return fgets($this->handle);
    }

    /**
     * Primary method to use when iterating over the config file.
     */
    public function nextEntry() {
        $entry = null;

        // This section collapses multiline entries down to a single line
        do {
            // Say goodbye to the beginning and ending whitespace
            $n = trim($this->next());

            // Don't need the LINE_WRAP anymore after this
            $entry .= rtrim($n, Hosts::LINE_WRAP);
        } while(!$this->feof() && strlen($n) > 0 && $n{strlen($n)-1} === Hosts::LINE_WRAP);

        return Mapper::map($entry);
    }

    /**
     * Destructor
     */
    public function __destruct() {
        fclose($this->handle);
    }
}
