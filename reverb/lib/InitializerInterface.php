<?php

interface InitializerInterface
{
    public function Initialize($instance, DependencyContainer $dependencyContainer);
}
