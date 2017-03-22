<?php
namespace Xymon\Test;

trait TestSetupTrait
{
    public function setUp()
    {
        $this->uri = sprintf('http://%s/xymon-cgimsg/xymoncgimsg.cgi', gethostname());
    }
}
