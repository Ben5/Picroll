<?php

namespace Reverb\Gateway;

use Site\Config\SiteConfig;

require_once __DIR__."/gateway_base.php";

use Reverb\Gateway\GatewayBase;

class GatewayHtml extends GatewayBase
{
    private $scripts = array();
    private $stylesheets = array();

    private function AddScript($scriptname)
    {
        $this->scripts[] = $scriptname;
    }

    private function AddStylesheet($cssname)
    {
        $this->stylesheets[] = $cssname;
    }

    public function ConstructOutput()
    {      
        $viewName = $this->componentInstance->GetViewName();
        if (is_null($viewName))
        {
            $viewName = $this->componentName;
        }

        $onlyTemplate = $this->componentInstance->GetOnlyTemplate();

        if ($onlyTemplate)
        {
            include $this->siteRoot.'/views/'.$viewName.'.php';
        }
        else
        {
            // get any variables that the Component exposed for use in the View
            $outputVars = $this->componentInstance->GetExposedVariables();
            foreach($outputVars as $name => $value)
            {
                $$name = $value;
            }

            // get the page title, for use in the navbar template
            $navbarTitle = $this->componentInstance->GetPageTitle();

            // read in the navbar template
            $navbar = '';
            if ( is_readable($this->siteRoot.'/views/nav.php') )
            {
                ob_start(); // use output buffering so that we can require the file and have embedded php executed
                require $this->siteRoot.'/views/nav.php';
                // $navbar is available to be echoed in the layout file.
                $navbar = ob_get_clean();
            }

            // read in the view template
            if( !is_readable($this->siteRoot.'/views/'.$viewName.'.php') )
            {
                trigger_error('cannot find specified view: '.$viewName);
            }
            ob_start(); // use output buffering so that we can require the file and have embedded php executed
            require $this->siteRoot.'/views/'.$viewName.'.php';
            // $content is available to be echoed in the layout file.
            $content = ob_get_clean();

            $headVarString = $this->componentInstance->GetHeadVariables();

            // add meta tags
            $headVarString .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';

            // include any page-specific stylesheets
            if($this->projectName == '') 
            {
                // this is a weird one, there is no project (which really means it is the top-level project)
                $headVarString .= '<link rel="stylesheet" type="text/css" href="/css/'.$this->componentName.'.css" />'."\n";
            }
            else
            {
                // this one isn't weird, just add it to the list
                $this->AddStylesheet($this->componentName.'.css');
            }

            // include global css files
            $globalCssFiles = array(
                                 'bootstrap.min.css', 
                                 'bootstrap-theme.min.css', 
                                 );
            foreach($globalCssFiles as $filename)
            {
                $headVarString .= '<link rel="stylesheet" type="text/css" href="/css/'.$filename.'" />'."\n";
            }

            // include any page-specific css files
            $this->AddStylesheet('nav.css');
            $this->AddStylesheet('style.css');
            foreach($this->stylesheets as $cssFilename)
            {
                $headVarString .= '<link rel="stylesheet" type="text/css" href="/'.$this->projectName.'/css/'.$cssFilename.'" />'."\n";
            }

            // Include global js files 
            $endOfBodyJavascript = '<!-- Placed at the end of the document so the pages load faster -->'."\n";
            $globalJsFiles = array('jquery-1.10.2.min.js',
                                 'jquery-ui.min.js', 
                                 'jquery.ui.accordion.min.js', 
                                 'bootstrap.min.js', 
                                 'bootbox.min.js', 
                                 );
            foreach($globalJsFiles as $filename)
            {
                $endOfBodyJavascript .= '<script type="text/javascript" src="/js/'.$filename.'">'."</script>\n";
            }

            // include any page-specific javascript
            $this->AddScript($this->componentName.'.js');
            foreach($this->scripts as $scriptname)
            {
                $jsSrc = '/'.$this->projectName.'/js/'.$scriptname;
                $endOfBodyJavascript .= '<script type="text/javascript" src="'.$jsSrc.'"></script>'."\n";
            }

            include $this->siteRoot.'/views/layout.php';
        }
    }
}


$gateway = new GatewayHtml;
$gateway->Prepare();
$gateway->ConstructOutput();
