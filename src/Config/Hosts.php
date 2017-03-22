<?php
namespace Xymon\Config;

/**
 * Constants and such.
 *
 * @author chey
 */
abstract class Hosts {
    // Things we find in hosts.cfg
    const COMMENT = '#';
    const LINE_WRAP = '\\';
    const LINE_END = PHP_EOL;
    const WHITESPACE = ' ';

    // Supported Xymon options
    const XMH_OPT_PAGE = 'page';
    const XMH_OPT_SUBPAGE = 'subpage';
    const XMH_OPT_SUBPARENT = 'subparent';
    const XMH_OPT_VPAGE = 'vpage';
    const XMH_OPT_GROUP = 'group';
    const XMH_OPT_INCLUDE = 'include';
    const XMH_OPT_DISPINCLUDE = 'dispinclude';
    const XMH_OPT_NETINCLUDE = 'netinclude';
    const XMH_OPT_DIRECTORY = 'directory';
    const XMH_OPT_OPTIONAL = 'optional';
}
