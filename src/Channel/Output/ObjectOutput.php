<?php
namespace Xymon\Channel\Output;

use Xymon\Channel\ChannelMessage;
use Xymon\Channel\ChannelReader as Parser;
use Xymon\Message\Factory;
use Xymon\Message\UnknownMessage;

class ObjectOutput extends OutputAbstract
{
    /**
     * @return Xymon\Channel\ChannelMessage
     */
    public function getObject($fallback = UnknownMessage::class)
    {
        $parsed = Parser::parse($this->message);
        $cm = new ChannelMessage($parsed['channel'], $parsed['sequence']);
        if (!empty($parsed['metadata'])) {
            foreach ($parsed['metadata'] as $m) {
                $cm->addMetaItem($m);
            }
        }
        if (!empty($parsed['body'])) {
            $cm->setBody(Factory::create($parsed['body'], $fallback));
        }
        return $cm;
    }
}
