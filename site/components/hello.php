<?php
include(REVERB_ROOT."/system/component.php");

class Hello extends ComponentBase
{

    protected function 
    Index($params)
    {
        $this->ExposeVariable("msg", "Hello everybody!"); 
    }

}
