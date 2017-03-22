<?php

namespace Xymon\Config\Hosts\Entry;

/**
 * Xymon Include entry.
 *
 * @author chey
 */
class IncludeEntry extends Entry {
    public function getOptArgs() {
        return array(
            'option',
            'filename'
        );
    }
}
