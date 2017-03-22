<?php
namespace Xymon\Message\Traits;

trait Hostname
{
    public function setHostname($hostname)
    {
        if (!$hostname) {
            throw new \InvalidArgumentException(sprintf('Invalid hostname: %s', $hostname));
        }

        $this->data['hostname'] = str_replace('.', ',', $hostname);
    }
}
