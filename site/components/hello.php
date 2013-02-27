<?php

use Reverb\SiteConfig;

include(SiteConfig::REVERB_ROOT."/system/componentbase.php");

class Hello extends ComponentBase
{
    protected function
    RequiresAuthentication()
    {
        return false;
    }

    protected function 
    Index($params)
    {
        $this->ExposeVariable("msg", "Hello everybody!"); 
    }

}
