<?php

namespace Reverb\Lib;

use Reverb\System\DependencyContainer;

interface InitializerInterface
{
    public function Initialize($instance, DependencyContainer $dependencyContainer);
}
