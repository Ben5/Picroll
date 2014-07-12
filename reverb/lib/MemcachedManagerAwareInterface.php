<?php

namespace Reverb\Lib;

use Reverb\Lib\MemcachedManager;

interface MemcachedManagerAwareInterface 
{
    public function GetMemcachedManager();
    public function SetMemcachedManager(MemcachedManager $instance);
}
