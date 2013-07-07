<?php

use Picroll\SiteConfig;

include(SiteConfig::REVERB_ROOT."/system/componentbase.php");

class View extends ComponentBase
{
    protected function
    RequiresAuthentication()
    {
        return true;
    }

    protected function 
    Index($params)
    {
        $this->ExposeVariable("msg", "Hello everybody!"); 
    }

    protected function
    ViewAllImages($params)
    {
        // read everything in /opt/git/Picroll/site/images/uploads/...
        $images = array_values(array_diff(scandir('/opt/git/Picroll/site/images/uploads'), array('.', '..', '.gitignore')));

        $this->ExposeVariable('images', $images);
    }
}
