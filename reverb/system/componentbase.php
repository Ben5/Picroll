<?php

namespace Reverb\System;

use Site\Config\SiteConfig;
use Reverb\System\DependencyContainer;

class ComponentBase
{
    private $headVars     = array();
    private $outputVars   = array();
    private $viewName     = null;
    private $onlyTemplate = false;

    private $memcachedManager    = null;
    private $dependencyContainer = null;

    public function
    __construct(DependencyContainer $dependencyContainer)
    {
        $this->dependencyContainer = $dependencyContainer;
        $this->memcachedManager    = $dependencyContainer->GetInstance('MemcachedManager');
    }

    protected function
    GetDependencyContainer()
    {
        return $this->dependencyContainer;
    }

    protected function
    GetMemcachedManager()
    {
        return $this->memcachedManager;
    }

    public function
    Prepare($action, $params)
    {
        if (!session_id()) {
            session_start();
        }

        if ((method_exists($this, 'RequiresAuthentication')) && ($this->RequiresAuthentication()))
        {
            // we want authentication for this action. Are we logged in?
            if (!isset($_SESSION['logged_in']))
            {
                // not logged in, redirect to login page.
                header('Location: /picroll/html/login/index');
            }
        }


        if (!method_exists($this, $action))
        {
            trigger_error("unknown action: $action");
            exit();
        }

        $this->$action($params);
    }

    protected function 
    ExposeVariable(
        $name,
        $value,
        $isHeadVar = false )
    {
        if ($isHeadVar)
        {
            if (isset($this->headVars[$name]))
            {
                trigger_error("duplicate output variable: $name");
            }

            $this->headVars[$name] = $value;
        }
        else
        {
            if (isset($this->outputVars[$name]))
            {
                trigger_error("duplicate output variable: $name");
            }

            $this->outputVars[$name] = $value;
        }

    }

    public function
    SetPageTitle($title)
    {
        $this->headVars['title'] = $title;
    }

    public function
    GetPageTitle()
    {
        if (!isset($this->headVars['title']))
        {
            return SiteConfig::DEFAULT_HEAD_TITLE;
        }
        return $this->headVars['title'];
    }

    public function
    GetHeadVariables()
    {
        $headVarString = '';

        if (!isset($this->headVars['title']))
        {
            $this->headVars['title'] = SiteConfig::DEFAULT_HEAD_TITLE;
        }

        foreach($this->headVars as $name => $value)
        {
            $headVarString .= '<'.$name.'>'.$value.'</'.$name.">\n";
        }

        return $headVarString;
    }

    public function
    GetExposedVariables()
    {
        return $this->outputVars;
    }

    public function
    SetViewName($viewName)
    {
        $this->viewName = $viewName;
    }

    public function
    GetViewName()
    {
        return $this->viewName;
    }

    public function
    SetOnlyTemplate($onlyTemplate)
    {
        $this->onlyTemplate = $onlyTemplate;
    }

    public function
    GetOnlyTemplate()
    {
        return $this->onlyTemplate;
    }

    protected function
    ValidateParams(
        $params,
        array $expectedKeys)
    {
        foreach($expectedKeys as $key => $type)
        {
            if (!isset($params[$key]))
            {
                throw new Exception('Key "'.$key.'" not found in parameters array.');
            }
        }
    }
}
