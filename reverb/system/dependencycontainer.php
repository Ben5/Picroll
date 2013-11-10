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
            $params = array();
            foreach ($dependency['dependencies'] as $param) {
                $params[] = $this->GetInstance($param);
            }
            $class = new ReflectionClass($instanceName);
            $instance = $class->newInstanceArgs($params);
            $this->instances[$instanceName] = $instance;
        }

        return $this->instances[$instanceName];
    }
}
