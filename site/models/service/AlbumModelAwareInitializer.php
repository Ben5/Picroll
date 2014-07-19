<?php

namespace Site\Models\Service;

use Reverb\Lib\InitializerInterface;
use Reverb\System\DependencyContainer;

class AlbumModelAwareInitializer implements InitializerInterface
{
    public function Initialize($instance, DependencyContainer $dependencyContainer)
    {
        if ($instance instanceof AlbumModelAwareInterface) {
            $instance->SetAlbumModel($dependencyContainer->GetInstance('AlbumModel'));
        }
    }
}
