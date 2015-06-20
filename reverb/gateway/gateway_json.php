<?php

namespace Reverb\Gateway;

require_once(__DIR__."/gateway_base.php");

class GatewayJson extends GatewayBase
{

    public function ConstructOutput()
    {      
        $outputVars = $this->componentInstance->GetExposedVariables();

        echo json_encode($outputVars);
    }

}


$gateway = new GatewayJson;
$gateway->Prepare();
$gateway->ConstructOutput();
