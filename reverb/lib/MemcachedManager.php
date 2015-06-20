<?php

namespace Reverb\Lib;

define('CACHE_TIME_DAY', 60 * 60 * 24);
define('CACHE_TIME_WEEK', 60 * 60 * 24 * 7);

class MemcachedManager 
{
    private $memcached = null;

    public function __construct()
    {
        $instance = new \Memcached();

        if (count($instance->getServerList()) === 0) {
            $instance->addServer('localhost', 11211);
        }

        $this->memcached = $instance;
    }

    public function Get($key)
    {
        return $this->memcached->get($key);
    }

    public function Set($key, $value, $expiration)
    {
        return $this->memcached->set($key, $value, $expiration);
    }

    public function Delete($key, $time = 0)
    {
        if (is_array($key)) {
            return $this->memcached->deleteMulti($key, $time);
        } else {
            return $this->memcached->delete($key, $time);
        }
    }

    // Warning, this function blindly deletes all cached data!
    public function Flush()
    {
        $this->memcached->flush();
    }
}
