<?php

use Picroll\SiteConfig;

require_once SiteConfig::REVERB_ROOT."/lib/InitializerInterface.php";

class DbConnectionAwareInitializer implements InitializerInterface
{
    public function Initialize($instance, DependencyContainer $dependencyContainer)
    {
        if ($instance instanceof DbConnectionAwareInterface) {
            $instance->SetDbConnection($dependencyContainer->GetInstance('DbConnection'));
        }
    }
}
