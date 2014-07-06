<?php

use Picroll\SiteConfig;

require_once SiteConfig::REVERB_ROOT."/system/modelbase.php";
require_once SiteConfig::REVERB_ROOT."/lib/MemcachedManagerAwareInterface.php";

// Memcached Keys
define('MKEY_ALBUMS_BY_USER_ID', 'GetAllAlbumsByUserId_');

class AlbumModel extends ModelBase
    implements MemcachedManagerAwareInterface
{
    private $memcachedManager;

    public function __construct()
    {
        $this->modelName = "album";
    }

    public function GetMemcachedManager()
    {
        return $this->memcacherManager;
    }

    public function SetMemcachedManager(MemcachedManager $instance)
    {
        $this->memcachedManager = $instance;
    }

    public function GetAllAlbumsByUserId($userId)
    {
        $allAlbums = $this->memcachedManager->get(MKEY_ALBUMS_BY_USER_ID.$userId);

        if ($allAlbums === false) {
            $sql = 'SELECT id, name, date_created, COUNT(image_id) AS size
                    FROM   album
                    JOIN album_content ON album.id = album_content.album_id
                    WHERE  user_id = ?
                    GROUP BY album_content.album_id';

            $query = DbInterface::NewQuery($sql);

            $query->AddStringParam($userId);

            $allAlbums = $query->TryReadRowArray();
            $this->memcachedManager->set(MKEY_ALBUMS_BY_USER_ID.$userId, $allAlbums, CACHE_TIME_DAY);
        }
        
        return $allAlbums;
    }

    public function AddNewAlbum(
       $userId, 
       $name)
    {
        $sql = "INSERT INTO album (user_id, name)
                VALUES (?, ?)";

        $query = DbInterface::NewQuery($sql);
        $query->AddStringParam($userId);
        $query->AddStringParam($name);

        $newId = $query->TryExecuteInsert();
        if ($newId === false) {
            var_dump($query->GetLastError());exit;
        }

        // added a new album, clear the old cached albums list
        $this->memcachedManager->Delete(MKEY_ALBUMS_BY_USER_ID.$userId);

        return $newId;
    }

    public function AddContentToAlbum(
        $albumId,
        array $imageIdArray,
        $userId)
    {
        $sql = 'INSERT IGNORE INTO album_content
                (album_id, image_id)
                VALUES ';

        $sql .= rtrim(str_repeat('(?, ?),', count($imageIdArray)), ',');

        $query = DbInterface::NewQuery($sql);
        foreach ($imageIdArray as $imageId) {
            $query->AddIntegerParam($albumId);
            $query->AddIntegerParam($imageId);
        }

        $newId = $query->TryExecuteInsert();

        if ($newId !== false) {
            // altered an album, clear the old cached albums list
            $this->memcachedManager->Delete(MKEY_ALBUMS_BY_USER_ID.$userId);
        }
        
        return $newId;
    }

    public function RemoveImages(
        $albumId,
        $imageIds,
        $userId)
    {
        $sql = 'DELETE FROM album_content
                WHERE album_id = ?
                AND   image_id in ';
        $sql .= '(' . implode(',', $imageIds) . ')';

        $query = DbInterface::NewQuery($sql);
        $query->AddIntegerParam($albumId);

        $query->ExecuteDelete('Unable to remove images from album');

        // deleted from an album, clear the old cached albums list
        $this->memcachedManager->Delete(MKEY_ALBUMS_BY_USER_ID.$userId);
}

    public function DeleteAlbum(
        $userId, 
        $albumId)
    {
        // First delete the contents from the album
        $sql = 'DELETE FROM album_content
                WHERE album_id = ?';
        $query = DbInterface::NewQuery($sql);
        $query->AddIntegerParam($albumId);
        $query->ExecuteDelete('Unable to delete contents from album');


        // Now delete the album itself
        $sql = 'DELETE FROM album 
                WHERE user_id = ?
                AND   id = ?';
        $query = DbInterface::NewQuery($sql);
        $query->AddIntegerParam($userId);
        $query->AddIntegerParam($albumId);
        $query->ExecuteDelete('Unable to delete album');

        // deleted from an album, clear the old cached albums list
        $this->memcachedManager->Delete(MKEY_ALBUMS_BY_USER_ID.$userId);
    }
}
