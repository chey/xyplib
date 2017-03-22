<?php

namespace Xymon\Config\Hosts;

use Xymon\Config\Hosts;

/**
 * Map Xymon hosts.cfg entry to its matching class.
 *
 * @author chey
 */
class Mapper {
    /**
     * Determine if we have a Comment or a Host entry, initialize and return that object.
     */
    public static function map($input) {
        $obj = null;
        if (empty($input)) {
            // Nada
        } elseif ($input{0} === Hosts::COMMENT) {
            $obj = Entry\CommentEntry::create($input);
        } elseif (is_numeric($input{0})) {
            $obj = Entry\HostEntry::create($input);
        } elseif (strncmp($input, Hosts::XMH_OPT_PAGE, 4) == 0) {
            $obj = Entry\PageEntry::create($input);
        } elseif (strncmp($input, Hosts::XMH_OPT_SUBPAGE, 7) == 0) {
            $obj = Entry\PageEntry::create($input);
        } elseif (strncmp($input, Hosts::XMH_OPT_SUBPARENT, 9) == 0) {
            $obj = Entry\SubparentEntry::create($input);
        } elseif (strncmp($input, Hosts::XMH_OPT_GROUP, 5) == 0) {
            $obj = Entry\GroupEntry::create($input);
        } elseif (strncmp($input, Hosts::XMH_OPT_INCLUDE, 7) == 0) {
            $obj = Entry\IncludeEntry::create($input);
        } elseif (strncmp($input, Hosts::XMH_OPT_DIRECTORY, 9) == 0) {
            $obj = Entry\DirectoryEntry::create($input);
        } elseif (strncmp($input, Hosts::XMH_OPT_OPTIONAL, 8) == 0) {
            if (strncmp(ltrim(substr($input, 8)), Hosts::XMH_OPT_INCLUDE, 7) == 0) {
                $obj = Entry\IncludeEntry::create($input);
            } elseif (strncmp(ltrim(substr($input, 8)), Hosts::XMH_OPT_DIRECTORY, 9) == 0) {
                $obj = Entry\DirectoryEntry::create($input);
            } else {
                // We'll only end up here if someone puts 'optional' where
                // it doesn't belong.
                // This basically chops off the optional tag and re-runs the
                // input through the mapper. That way if someone stuck 'optional' in
                // front of something they shouldn't have, it _should_ still be
                // mapped properly.
                $obj = Mapper::map(ltrim(substr($input, 8)));
            }
        }
        return $obj;
    }
}
