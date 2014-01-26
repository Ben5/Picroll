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

    public function __construct() 
    {
        $this->classes = array(
            // Libs
            'MemcachedManager' => array(
                'path' => self::REVERB_ROOT . "/lib/MemcachedManager.php",
                'dependencies' => array(
                ),
            ),
            // Models
            'AlbumModel' => array(
                'path' => self::SITE_ROOT . "/models/album.php",
                'dependencies' => array(
                    'MemcachedManager',
                ),
            ),
            'FriendModel' => array(
                'path' => self::SITE_ROOT . "/models/friend.php",
                'dependencies' => array(
                ),
            ),
            'FriendRequestModel' => array(
                'path' => self::SITE_ROOT . "/models/friend_request.php",
                'dependencies' => array(
                ),
            ),
            'ImageModel' => array(
                'path' => self::SITE_ROOT . "/models/image.php",
                'dependencies' => array(
                    'MemcachedManager',
                ),
            ),
            'UserModel' => array(
                'path' => self::SITE_ROOT . "/models/user.php",
                'dependencies' => array(
                ),
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
}
