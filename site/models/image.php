<?php

use Picroll\SiteConfig;

require_once SiteConfig::REVERB_ROOT."/system/modelbase.php";

// Memcached Keys
define('MKEY_IMAGES_BY_USER_ID', 'GetAllImagesByUserId_');
define('MKEY_IMAGES_BY_ALBUM_ID', 'GetAllImagesByAlbumId_');
define('MKEY_ALBUM_CACHE_KEYS_BY_USER', 'CacheKEysForAlbumsByUserId_');

class ImageModel extends ModelBase
{
    public function
    __construct($memcachedManager)
    {
        $this->modelName = "image";
        $this->memcachedManager = $memcachedManager;
    }

    public function 
    GetAllImagesByUserId($userId)
    {
        $allImages = $this->memcachedManager->Get(MKEY_IMAGES_BY_USER_ID.$userId);

        if ($allImages === false) {
            $sql = 'SELECT id, filename
                    FROM   image
                    WHERE  user_id = ?';

            $query = DbInterface::NewQuery($sql);

            $query->AddStringParam($userId);

            $allImages = $query->TryReadDictionary();
            $this->memcachedManager->Set(MKEY_IMAGES_BY_USER_ID.$userId, $allImages, CACHE_TIME_DAY);
        }

        return $allImages;
    }

    public function 
    GetAllImagesByAlbumId(
        $albumId,  
        $userId)
    {
        $allImages = $this->memcachedManager->Get(MKEY_IMAGES_BY_ALBUM_ID.$albumId);

        if ($allImages === false) {
            $sql = 'SELECT image.id, filename
                    FROM   image
                    JOIN   album_content ON album_content.image_id = image.id
                    WHERE  album_content.album_id = ?';

            $query = DbInterface::NewQuery($sql);

            $query->AddIntegerParam($albumId);

            $allImages = $query->TryReadDictionary();

            // update caches
            $this->memcachedManager->Set(MKEY_IMAGES_BY_ALBUM_ID.$albumId, $allImages, CACHE_TIME_DAY);
            $cachedAlbumKeys = $this->memcachedManager->Get(MKEY_ALBUM_CACHE_KEYS_BY_USER.$userId);
            if ($cachedAlbumKeys === false) {
                $cachedAlbumKeys = array();
            }
            $cachedAlbumKeys[] = MKEY_IMAGES_BY_ALBUM_ID.$albumId;
            $this->memcachedManager->Set(MKEY_ALBUM_CACHE_KEYS_BY_USER.$userId, $cachedAlbumKeys, CACHE_TIME_DAY);
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
            $this->memcachedManager->Delete(MKEY_IMAGES_BY_USER_ID.$userId);
            // get the keys of albums that have been stored
            $albumKeys = $this->memcachedManager->Get(MKEY_ALBUM_CACHE_KEYS_BY_USER.$userId);
            $this->memcachedManager->Delete($albumKeys);
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
        $this->memcachedManager->Delete(MKEY_IMAGES_BY_USER_ID.$userId);
        // get the keys of albums that have been stored
        $albumKeys = $this->memcachedManager->Get(MKEY_ALBUM_CACHE_KEYS_BY_USER.$userId);
        $this->memcachedManager->Delete($albumKeys);

        $this->memcachedManager->Delete(MKEY_ALBUMS_BY_USER_ID.$userId);
    }
    
}
