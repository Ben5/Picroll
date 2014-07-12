<?php

namespace Reverb\System;

use Picroll\SiteConfig;
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

            // TODO: delete this line, and fix by renaming all Model classes to be the same as the filename!!
            require_once $dependency['path'];

            $instance = new $dependency['fqcn'];
            
            // foreach initializer, call initialize
            // this will look to see if $instance implements an AwareInterface and either inject something or ignore it
            $initializers = $this->siteConfig->GetInitializers();

            foreach ($initializers as $initializerClass => $paths) {
                $initializerInstance = new $paths['fqcn'];

                if (!$initializerInstance instanceof InitializerInterface) {
                    die('Invalid Initializer: ' . $paths['fqcn']);
                }

                $initializerInstance->Initialize($instance, $this);
            }
            
            $this->instances[$instanceName] = $instance;
        }

        return $this->instances[$instanceName];
    }
}
