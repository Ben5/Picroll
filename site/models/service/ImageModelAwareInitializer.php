<?php

namespace Site\Models\Service;

use Reverb\Lib\InitializerInterface;
use Reverb\System\DependencyContainer;

class ImageModelAwareInitializer implements InitializerInterface
{
    public function Initialize($instance, DependencyContainer $dependencyContainer)
    {
        if ($instance instanceof ImageModelAwareInterface) {
            $instance->SetImageModel($dependencyContainer->GetInstance('ImageModel'));
        }
    }
}
