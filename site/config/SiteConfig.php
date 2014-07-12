<?php
namespace Site\Config;

class SiteConfig
{
    const WEB_ROOT   = "/opt/git/Picroll";
    const SITE_ROOT   = "/opt/git/Picroll/site";
    const REVERB_ROOT = "/opt/git/Picroll/reverb";
    const DEFAULT_HEAD_TITLE = "PicRoll";

    const DB_HOST = 'localhost';
    const DB_USER = 'db_user';
    const DB_PASS = 'wpe84u9384u5';
    const DB_DB   = 'picroll';

    const DEFAULT_PAGE_AFTER_LOGIN = '/picroll/html/view';

    private $classes = array();
    private $initializers = array();

    public function __construct() 
   {
        $this->classes = array(
            // Libs
            'MemcachedManager'      => 'Reverb\Lib\MemcachedManager',
            'DbConnection'          => 'Reverb\Lib\DbConnection',
            // Models
            'AlbumModel'            => 'Site\Models\AlbumModel',
            'FriendModel'           => 'Site\Models\FriendModel',
            'FriendRequestModel'    => 'Site\Models\FriendRequestModel',
            'ImageModel'            => 'Site\Models\ImageModel',
            'UserModel'             => 'Site\Models\User',
        );

        $this->initializers = array(
            'MemcachedManagerAwareInitializer'  => 'Reverb\Lib\MemcachedManagerAwareInitializer',
            'DbConnectionAwareInitializer'      => 'Reverb\Lib\DbConnectionAwareInitializer',
        );
    }

    public function GetClass($className)
    {
        if (!isset($this->classes[$className])) {
            return false;
        }

        return $this->classes[$className];
    }

    public function GetInitializers()
    {
        return $this->initializers;
    }
}
