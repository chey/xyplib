<?php
namespace Xymon\Message;

class Rename extends Message
{
    use Traits\Hostname;

    protected $data = [
        'hostname' => null,
        'param1' => null, // either new hostname or old testname
        'param2' => null // either blank or new testname
    ];

    /**
     * @param string $hostname
     * @param string $newhostname
     * @return Rename
     */
    public static function host($hostname, $newhostname)
    {
        return new self(['hostname' => $hostname, 'param1' => $newhostname]);
    }

    /**
     * @param string $hostname
     * @param string $oldtestname
     * @param string $newtestname
     * @return Rename
     */
    public static function test($hostname, $oldtestname, $newtestname)
    {
        return new self(['hostname' => $hostname, 'param1' => $oldtestname, 'param2' => $newtestname]);
    }
}
