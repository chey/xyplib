<?php
namespace Xymon\Channel;

use Xymon\Utility\BasicEnum;

abstract class ChannelType extends BasicEnum
{
    const Clichg = 'clichg';
    const Client = 'client';
    const Data = 'data';
    const Enadis = 'enadis';
    const Notes = 'notes';
    const Page = 'page';
    const Stachg = 'stachg';
    const Status = 'status';
}
