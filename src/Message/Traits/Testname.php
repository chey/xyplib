<?php
namespace Xymon\Message\Traits;

trait Testname
{
    public function setTestname($testname)
    {
        if (preg_match('/\s/', $testname)) {
            throw new \InvalidArgumentException(sprintf('Invalid testname: %s', $testname));
        }

        $this->data['testname'] = $testname;
    }
}
