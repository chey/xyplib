<?php

namespace Xymon\Config\Hosts\Entry;

/**
 * Xymon hosts.cfg Page entry.
 *
 * @author chey
 */
class PageEntry extends Entry {
    public function getOptArgs() {
        return array(
            'option',
            'name',
            'title'
        );
    }
}
