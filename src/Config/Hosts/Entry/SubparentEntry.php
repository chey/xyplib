<?php

namespace Xymon\Config\Hosts\Entry;

/**
 * Xymon hosts.cfg subparent entry.
 *
 * @author chey
 */
class SubparentEntry extends Entry {
    public function getOptArgs() {
        return array(
            'option',
            'parent',
            'name',
            'title'
        );
    }
}
