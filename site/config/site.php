<?php
namespace Picroll;

class SiteConfig
{
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
            ),
            // Models
            'AlbumModel' => array(
                'path' => self::SITE_ROOT . "/models/album.php",
            ),
            'FriendModel' => array(
                'path' => self::SITE_ROOT . "/models/friend.php",
            ),
            'FriendRequestModel' => array(
                'path' => self::SITE_ROOT . "/models/friend_request.php",
            ),
            'ImageModel' => array(
                'path' => self::SITE_ROOT . "/models/image.php",
            ),
            'UserModel' => array(
                'path' => self::SITE_ROOT . "/models/user.php",
            ),
        );

        $this->initializers = array(
            'MemcachedManagerAwareInitializer' => self::REVERB_ROOT.'/lib/MemcachedManagerAwareInitializer.php',
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
