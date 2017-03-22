<?php
namespace Xymon\Message;

use Xymon\Utility\BasicEnum;

abstract class MessageType extends BasicEnum
{
    const Client = 'client';
    const Clientlog = 'clientlog';
    const Config = 'config';
    const Download = 'download';
    const Data = 'data';
    const Disable = 'disable';
    const Drop = 'drop';
    const Enable = 'enable';
    const Ghostlist = 'ghostlist';
    const Hostinfo = 'hostinfo';
    const Modify = 'modify';
    const Notes = 'notes';
    const Notify = 'notify';
    const Pullclient = 'pullclient';
    const Ping = 'ping';
    const Query = 'query';
    const Rename = 'rename';
    const Schedule = 'schedule';
    const Status = 'status';
    const Usermsg = 'usermsg';
    const Xymondack = 'xymondack';
    const Xymondlog = 'xymondlog';
    const Xymondxlog = 'xymondxlog';
    const Xymondboard = 'xymondboard';
    const Xymondxboard = 'xymondxboard';
}
