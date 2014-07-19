<?php

namespace Site\Models\Service;

use Reverb\Lib\InitializerInterface;
use Reverb\System\DependencyContainer;

class FriendModelAwareInitializer implements InitializerInterface
{
    public function Initialize($instance, DependencyContainer $dependencyContainer)
    {
        if ($instance instanceof FriendModelAwareInterface) {
            $instance->SetFriendModel($dependencyContainer->GetInstance('FriendModel'));
        }
    }
}
