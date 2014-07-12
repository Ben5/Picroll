<?php
namespace Picroll;

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
            'MemcachedManager' => array(
                'path' => self::REVERB_ROOT . "/lib/MemcachedManager.php",
                'fqcn' => 'Reverb\Lib\MemcachedManager',
            ),
            'DbConnection' => array(
                'path' => self::REVERB_ROOT . "/lib/DbConnection.php",
                'fqcn' => 'Reverb\Lib\DbConnection',
            ),
            // Models
            'AlbumModel' => array(
                'path' => self::SITE_ROOT . "/models/album.php",
                'fqcn' => 'Site\Models\AlbumModel',
            ),
            'FriendModel' => array(
                'path' => self::SITE_ROOT . "/models/friend.php",
                'fqcn' => 'Site\Models\FriendModel',
            ),
            'FriendRequestModel' => array(
                'path' => self::SITE_ROOT . "/models/friend_request.php",
                'fqcn' => 'Site\Models\FriendRequestModel',
            ),
            'ImageModel' => array(
                'path' => self::SITE_ROOT . "/models/image.php",
                'fqcn' => 'Site\Models\ImageModel',
            ),
            'UserModel' => array(
                'path' => self::SITE_ROOT . "/models/user.php",
                'fqcn' => 'Site\Models\User',
            ),
        );

        $this->initializers = array(
            'MemcachedManagerAwareInitializer' => array(
                'path' => self::REVERB_ROOT.'/lib/MemcachedManagerAwareInitializer.php',
                'fqcn' => 'Reverb\Lib\MemcachedManagerAwareInitializer',
            ),
            'DbConnectionAwareInitializer' => array(
                'path' => self::REVERB_ROOT.'/lib/DbConnectionAwareInitializer.php',
                'fqcn' => 'Reverb\Lib\DbConnectionAwareInitializer',
            ),
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
