<?php

namespace Reverb\Lib;

use Site\Config\SiteConfig;
use Reverb\Lib\InitializerInterface;
use Reverb\System\DependencyContainer;

class DbConnectionAwareInitializer implements InitializerInterface
{
    public function Initialize($instance, DependencyContainer $dependencyContainer)
    {
        if ($instance instanceof DbConnectionAwareInterface) {
            $instance->SetDbConnection($dependencyContainer->GetInstance('DbConnection'));
        }
    }
}
