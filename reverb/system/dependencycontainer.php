<?php

namespace Reverb\System;

use Site\Config\SiteConfig;
use Reverb\Lib\InitializerInterface;

class DependencyContainer
{
    private $instances = array();
    private $siteconfig;

    public function
    __construct(SiteConfig $siteConfig)
    {
        $this->siteConfig = $siteConfig;
    }

    public function
    GetInstance($instanceName)
    {
        if (false === $this->siteConfig->GetClass($instanceName)) {
            die('unknown class ' . $instanceName);
        }

        if (empty($this->instances[$instanceName])) {
            $dependency = $this->siteConfig->GetClass($instanceName);

            $instance = new $dependency;
            
            // foreach initializer, call initialize
            // this will look to see if $instance implements an AwareInterface and either inject something or ignore it
            $initializers = $this->siteConfig->GetInitializers();

            foreach ($initializers as $initializerClass => $path) {
                $initializerInstance = new $path;

                if (!$initializerInstance instanceof InitializerInterface) {
                    die('Invalid Initializer: ' . $path);
                }

                $initializerInstance->Initialize($instance, $this);
            }
            
            $this->instances[$instanceName] = $instance;
        }

        return $this->instances[$instanceName];
    }
}
