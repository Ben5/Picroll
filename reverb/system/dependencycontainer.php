<?php
use Picroll\SiteConfig;

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

            require_once $dependency['path'];

            $instance = new $instanceName;
            
            // foreach initializer, call initialize
            // this will look to see if $instance implements an AwareInterface and either inject something or ignore it
            $initializers = $this->siteConfig->GetInitializers();

            require_once SiteConfig::REVERB_ROOT."/lib/InitializerInterface.php";
            foreach ($initializers as $initializerClass => $path) {
                require_once $path;
                $initializerInstance = new $initializerClass;

                if (!$initializerInstance instanceof InitializerInterface) {
                    die('Invalid Initializer');
                }

                $initializerInstance->Initialize($instance, $this);
            }
            
            $this->instances[$instanceName] = $instance;
        }

        return $this->instances[$instanceName];
    }
}
