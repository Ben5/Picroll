<?php

namespace Reverb\Gateway;

use Picroll\SiteConfig;
use Reverb\System\DependencyContainer;
use Reverb\System\Error;
use Site\Components;

require_once "/opt/git/Picroll/site/config/site.php";

// Error Handling
set_error_handler("Reverb\System\Error::ErrorHandler");

class GatewayBase 
{
    protected $siteRoot;
    protected $projectName;
    protected $componentName;
    protected $fullyQualifiedComponentName;
    protected $componentInstance;
    protected $siteConfig;

    public function prepare()
    {
        $this->componentName = '';
        $this->siteRoot = SiteConfig::SITE_ROOT;
        $this->projectName = '';

        $action = 'Index';
        $params = array();
        
        foreach( $_REQUEST as $param=>$val )
        {
            switch( $param )
            {
                case "_project":
                {
                    $this->projectName = $val;
                }
                break;

                case "_component":
                {
                    $this->componentName = $val;
                }
                break;

                case "_action":
                {
                    $action = $val;
                }
                break;

                default:
                {
                    $params[$param] = $val;
                }
            }
        }

        if($this->componentName == "")
        {
            trigger_error("no component specified");
        }


        if( !is_readable($this->siteRoot."/components/$this->componentName.php") )
        {
            trigger_error('cannot find specified component: '.$this->componentName.' with site root: '.$this->siteRoot);
        }

        include $this->siteRoot."/components/$this->componentName.php";

        $this->fullyQualifiedComponentName = "Site\Components\\" . $this->componentName;

        if( !class_exists($this->fullyQualifiedComponentName) )
        {
            trigger_error("cannot find specified class: $this->fullyQualifiedComponentName");
        }

        $siteConfig = new SiteConfig();
        require_once SiteConfig::REVERB_ROOT."/system/dependencycontainer.php";
        $dependencyContainer = new DependencyContainer($siteConfig);
        $this->componentInstance = new $this->fullyQualifiedComponentName($dependencyContainer);

        $this->componentInstance->Prepare($action, $params);
    }
}
