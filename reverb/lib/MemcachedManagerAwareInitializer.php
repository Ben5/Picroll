<?php

namespace Reverb\Lib;

use Site\Config\SiteConfig;
use Reverb\Lib\InitializerInterface;
use Reverb\System\DependencyContainer;

class MemcachedManagerAwareInitializer implements InitializerInterface
{
    public function Initialize($instance, DependencyContainer $dependencyContainer) 
    {
        if ($instance instanceof MemcachedManagerAwareInterface) {
            $instance->SetMemcachedManager($dependencyContainer->GetInstance('MemcachedManager'));
        }
    }
}
