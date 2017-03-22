<?php

namespace Xymon\Config\Hosts\Entry;

/**
 * Xymon Directory entry.
 *
 * @author chey
 */
class DirectoryEntry extends Entry {
    public function getOptArgs() {
        return array(
            'option',
            'directory'
        );
    }
}
