<?php

namespace Reverb\Gateway;

require_once "/opt/git/Picroll/site/config/SiteConfig.php";
use Site\Config\SiteConfig;
use Reverb\System\DependencyContainer;
use Reverb\System\Error;
use Site\Components;

require_once SiteConfig::VENDOR_ROOT . "/autoload.php";

// Autoloader
function autoload($class)
{
    $pathParts = explode("\\", $class);
    // Firstly we try to find the class with a lower case path, but take the case of the filename as it comes through
    $dirPath = strtolower(implode(DIRECTORY_SEPARATOR, array_slice($pathParts, 0, count($pathParts) - 1)));
    $fullPath = \Site\Config\SiteConfig::WEB_ROOT . DIRECTORY_SEPARATOR . $dirPath . DIRECTORY_SEPARATOR . $pathParts[count($pathParts) - 1] . ".php";

    if (is_readable($fullPath)) {
        require_once $fullPath;
    } else {
        // try it with a lower case filename
        $fullPath = SiteConfig::WEB_ROOT . DIRECTORY_SEPARATOR . $dirPath . DIRECTORY_SEPARATOR . strtolower($pathParts[count($pathParts) - 1]) . ".php";

        if (is_readable($fullPath)) {
            require_once $fullPath;
        } else {
            //var_dump($class, $fullPath); 
            //trigger_error('not readable');
        }
    }
}

spl_autoload_register('Reverb\Gateway\autoload');


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

        $this->fullyQualifiedComponentName = "Site\Components\\" . $this->componentName;

        $siteConfig = new SiteConfig();
        require_once SiteConfig::REVERB_ROOT."/system/dependencycontainer.php";
        $dependencyContainer = new DependencyContainer($siteConfig);

        $this->componentInstance = $dependencyContainer->GetInstance(ucfirst($this->componentName));

        $this->componentInstance->Prepare($action, $params);
    }
}
