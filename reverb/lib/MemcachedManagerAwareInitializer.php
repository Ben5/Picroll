<?php

use Picroll\SiteConfig;

require_once SiteConfig::REVERB_ROOT."/lib/InitializerInterface.php";

class MemcachedManagerAwareInitializer implements InitializerInterface
{
    public function Initialize($instance, $dependencyContainer) 
    {
        if ($instance instanceof MemcachedManagerAwareInterface) {
            $instance->SetMemcachedManager($dependencyContainer->GetInstance('MemcachedManager'));
        }
    }
}
