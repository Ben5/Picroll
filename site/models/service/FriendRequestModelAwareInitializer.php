<?php

namespace Site\Models\Service;

use Reverb\Lib\InitializerInterface;
use Reverb\System\DependencyContainer;

class FriendRequestModelAwareInitializer implements InitializerInterface
{
    public function Initialize($instance, DependencyContainer $dependencyContainer)
    {
        if ($instance instanceof FriendRequestModelAwareInterface) {
            $instance->SetFriendRequestModel($dependencyContainer->GetInstance('FriendRequestModel'));
        }
    }
}
