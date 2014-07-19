<?php

namespace Site\Models\Service;

use Reverb\Lib\InitializerInterface;
use Reverb\System\DependencyContainer;

class UserModelAwareInitializer implements InitializerInterface
{
    public function Initialize($instance, DependencyContainer $dependencyContainer)
    {
        if ($instance instanceof UserModelAwareInterface) {
            $instance->SetUserModel($dependencyContainer->GetInstance('UserModel'));
        }
    }
}
