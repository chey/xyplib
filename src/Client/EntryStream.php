<?php
namespace Xymon\Client;

use Xymon\Utility\Helper;
use Xymon\Config\Hosts\Entry\HostEntry;

class EntryStream extends ReadlineStream
{
    /**
     * @return Entry|false
     */
    public function nextEntry()
    {
        $line = $this->readline();
        if (!$line) {
            return false;
        }

        $lineArr = Helper::parseLine($line);
        $entry = new HostEntry;
        $entry->setHost(array_shift($lineArr));
        $entry->setIp(array_shift($lineArr));
        foreach ($lineArr as $tag) {
            $entry->addTag($tag);
        }
        return $entry;
    }
}
