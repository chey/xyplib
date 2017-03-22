<?php

namespace Xymon\Config\Hosts\Entry;

use Xymon\Validate;
use Xymon\Config\Hosts;

/**
 * Xymon Host entry.
 *
 * @author chey
 */
class HostEntry extends Entry
{
    private $tags = array();

    public function getOptArgs()
    {
        return array(
            'ip',
            'host'
        );
    }

    public function __construct($input = null)
    {
        call_user_func_array(array('parent', __FUNCTION__), func_get_args());
        $comment = $this->getComment();
        if (is_string($comment) && trim($comment) != '') {
            foreach (preg_split('/\s(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/', $comment) as $tag) {
                $this->addTag($tag);
            }
        }
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function addTag($tag)
    {
        $this->tags[] = $this->quoteTag($tag);
        $this->setComment(implode(Hosts::WHITESPACE, $this->getTags()));
    }

    public function setIp($ip)
    {
        if (!Validate::isIp($ip)) {
            throw new \InvalidArgumentException("$ip is not a valid ip address");
        }
        parent::setIp($ip);
    }

    public function setHost($host)
    {
        if (!Validate::isXyHost($host)) {
            throw new \InvalidArgumentException("$host is not a valid hostname");
        }
        parent::setHost($host);
    }

    /**
     * Properly quote a tag.
     * 
     * @param string $tag 
     * @return string
     */
    public function quoteTag($tag)
    {
        $tag = trim(str_replace(array('"', "\r", "\n"), '', $tag));
        if (preg_match('/\s+/', $tag)) {
            list($flag, $delim, $value) = preg_split('/([^\w])/', $tag, 2, PREG_SPLIT_DELIM_CAPTURE);
            $tag = sprintf('%s%s"%s"', $flag, $delim, $value);
        }

        return $tag;
    }
}
