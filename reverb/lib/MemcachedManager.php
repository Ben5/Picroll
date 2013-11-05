<?php

define('CACHE_TIME_DAY', 60 * 60 * 24);
define('CACHE_TIME_WEEK', 60 * 60 * 24 * 7);

class MemcachedManager 
{
    private $memcached = null;

    public function
    __construct() 
    {
        $instance = new Memcached();

        if (count($instance->getServerList()) === 0) {
            $instance->addServer('localhost', 11211);
        }

        //$instance->set('foo', 'bar');
        //$foo = $instance->get('foo');
        //var_dump($foo); die('cached!');

        $this->memcached = $instance;
    }

    public function 
    Get($key)
    {
        return $this->memcached->get($key);
    }

    public function
    Set($key, $value, $expiration)
    {
        return $this->memcached->set($key, $value, $expiration);
    }

    // Warning, this function blindly deletes all cached data!
    public function
    Flush()
    {
        $this->memcached->flush();
    }
}
