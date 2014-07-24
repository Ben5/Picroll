<?php

namespace Reverb\Lib;

use Site\Config\SiteConfig;
use Reverb\Lib\InitializerInterface;
use Reverb\System\DependencyContainer;
use \Zend\Db\Adapter\Adapter;

class DbAdapterAwareInitializer implements InitializerInterface
{
    public function Initialize($instance, DependencyContainer $dependencyContainer)
    {
        if ($instance instanceof DbAdapterAwareInterface) {
            $dbAdapter = new Adapter(array(
                'driver'   => 'Mysqli',
                'database' => SiteConfig::DB_DB,
                'username' => SiteConfig::DB_USER,
                'password' => SiteConfig::DB_PASS,
            ));

            $instance->SetDbAdapter($dbAdapter);
        }
    }
}
