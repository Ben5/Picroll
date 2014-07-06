<?php

interface MemcachedManagerAwareInterface {
    public function GetMemcachedManager();
    public function SetMemcachedManager(MemcachedManager $instance);
}
