<?php

use Picroll\SiteConfig;

require_once SiteConfig::REVERB_ROOT."/system/modelbase.php";
require_once SiteConfig::REVERB_ROOT."/lib/MemcachedManagerAwareInterface.php";

// Memcached Keys
define('MKEY_IMAGES_BY_USER_ID', 'GetAllImagesByUserId_');
define('MKEY_IMAGES_BY_ALBUM_ID', 'GetAllImagesByAlbumId_');
define('MKEY_ALBUM_CACHE_KEYS_BY_USER', 'CacheKEysForAlbumsByUserId_');

class ImageModel extends ModelBase
    implements MemcachedManagerAwareInterface
{
    private $memcachedManager;

    public function
    __construct()
    {
        $this->modelName = "image";
    }

    public function GetMemcachedManager()
    {
        return $this->memcachedManager;
    }

    public function SetMemcachedManager(MemcachedManager $instance)
    {
        $this->memcachedManager = $instance;
    }

    public function 
    GetAllImagesByUserId($userId)
    {
        $memcached = $this->GetMemcachedManager();
        $allImages = $memcached->Get(MKEY_IMAGES_BY_USER_ID.$userId);

        if ($allImages === false) {
            $sql = 'SELECT id, filename
                    FROM   image
                    WHERE  user_id = ?';

            $query = DbInterface::NewQuery($sql);

            $query->AddStringParam($userId);

            $allImages = $query->TryReadDictionary();
            $memcached->Set(MKEY_IMAGES_BY_USER_ID.$userId, $allImages, CACHE_TIME_DAY);
        }

        return $allImages;
    }

    public function 
    GetAllImagesByAlbumId(
        $albumId,  
        $userId)
    {
        $memcached = $this->GetMemcachedManager();

        $allImages = $memcached->Get(MKEY_IMAGES_BY_ALBUM_ID.$albumId);

        if ($allImages === false) {
            $sql = 'SELECT image.id, filename
                    FROM   image
                    JOIN   album_content ON album_content.image_id = image.id
                    WHERE  album_content.album_id = ?';

            $query = DbInterface::NewQuery($sql);

            $query->AddIntegerParam($albumId);

            $allImages = $query->TryReadDictionary();

            // update caches
            $memcached->Set(MKEY_IMAGES_BY_ALBUM_ID.$albumId, $allImages, CACHE_TIME_DAY);
            $cachedAlbumKeys = $memcached->Get(MKEY_ALBUM_CACHE_KEYS_BY_USER.$userId);
            if ($cachedAlbumKeys === false) {
                $cachedAlbumKeys = array();
            }
            $cachedAlbumKeys[] = MKEY_IMAGES_BY_ALBUM_ID.$albumId;
            $memcached->Set(MKEY_ALBUM_CACHE_KEYS_BY_USER.$userId, $cachedAlbumKeys, CACHE_TIME_DAY);
        }

        return $allImages;
    }

    public function 
    AddNewImage(
       $userId, 
       $filename)
    {
        $sql = "INSERT INTO image (user_id, filename)
                VALUES (?, ?)";

        $query = DbInterface::NewQuery($sql);
        $query->AddStringParam($userId);
        $query->AddStringParam($filename);

        $newId = $query->TryExecuteInsert();

        if ($newId !== false) {
            // new image added, clear caches
            $memcached = $this->GetMemcachedManager();
            $memcached->Delete(MKEY_IMAGES_BY_USER_ID.$userId);
            // get the keys of albums that have been stored
            $albumKeys = $memcached->Get(MKEY_ALBUM_CACHE_KEYS_BY_USER.$userId);
            $memcached->Delete($albumKeys);
        }

        return $newId;
    }

    public function 
    DeleteImage(
        $userId, 
        $imageId)
    {
        // First remove the image from all albums
        $sql = 'DELETE FROM album_content
                WHERE image_id = ?';
        $query = DbInterface::NewQuery($sql);
        $query->AddIntegerParam($imageId);
        $query->ExecuteDelete('Unable to remove image from albums');

        // Now delete the image
        $sql = 'DELETE FROM image 
                WHERE user_id = ?
                AND   id = ?';
        $query = DbInterface::NewQuery($sql);
        $query->AddIntegerParam($userId);
        $query->AddIntegerParam($imageId);

        $query->ExecuteDelete('Unable to delete image');

        // image deleted, clear caches
        $memcached = $this->GetMemcachedManager();
        $memcached->Delete(MKEY_IMAGES_BY_USER_ID.$userId);
        // get the keys of albums that have been stored
        $albumKeys = $memcached->Get(MKEY_ALBUM_CACHE_KEYS_BY_USER.$userId);
        $memcached->Delete($albumKeys);

        $memcached->Delete(MKEY_ALBUMS_BY_USER_ID.$userId);
    }
    
}
