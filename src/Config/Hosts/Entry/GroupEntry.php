<?php

namespace Xymon\Config\Hosts\Entry;

/**
 * Xymon Group entry.
 *
 * @author chey
 */
class GroupEntry extends Entry {
    public function getOptArgs() {
        return array(
            'option',
            'title'
        );
    }
}
