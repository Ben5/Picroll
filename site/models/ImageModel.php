<?php

namespace Site\Models;

use Site\Config\SiteConfig;
use Site\Models\Entities\ImageEntity;
use Reverb\System\ModelBase;
use Reverb\Lib\Collection;
use Reverb\Lib\MemcachedManager;
use Reverb\Lib\MemcachedManagerAwareInterface;
use \Zend\Db\Sql\Expression;
use \Zend\Db\Sql\Sql;

// Memcached Keys
define('MKEY_IMAGES_BY_USER_ID', 'GetAllImagesByUserId_');
define('MKEY_IMAGES_BY_ALBUM_ID', 'GetAllImagesByAlbumId_');
define('MKEY_ALBUM_CACHE_KEYS_BY_USER', 'CacheKeysForAlbumsByUserId_');

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
        $imageCollection = $memcached->Get(MKEY_IMAGES_BY_USER_ID.$userId);

        if ($imageCollection === false) {

            $sql = new Sql($this->getDbAdapter(), 'image');
            $select = $sql->select()
                ->columns(array(
                            'id'       => 'id',
                            'filename' => 'filename',
                            ))
                ->where(array(
                            'user_id' => $userId,
                            ));

            $statement = $sql->prepareStatementForSqlObject($select);
            $resultSet = $statement->execute();

            $imageCollection = new Collection(new ImageEntity(), $resultSet);

            $memcached->Set(MKEY_IMAGES_BY_USER_ID.$userId, $imageCollection, CACHE_TIME_DAY);
        }

        return $imageCollection;
    }

    public function 
    GetAllImagesByAlbumId(
        $albumId,  
        $userId)
    {
        $memcached = $this->GetMemcachedManager();

        $imageCollection = $memcached->Get(MKEY_IMAGES_BY_ALBUM_ID.$albumId);

        if ($imageCollection === false) {
            $sql = new Sql($this->getDbAdapter());
            $select = $sql->select()
                ->from(
                    array('i' => 'image'),
                    array(
                        'i.id', 
                        'i.filename',
                    )
                )
                ->join(
                    array('ac' => 'album_content'),
                    'ac.image_id = i.id'
                )
                ->where(
                    array(
                        'ac.album_id' => $albumId,
                    )
                );

            $statement = $sql->prepareStatementForSqlObject($select);
            $resultSet = $statement->execute();

            $imageCollection = new Collection(new ImageEntity(), $resultSet);

            // update caches
            $memcached->Set(MKEY_IMAGES_BY_ALBUM_ID.$albumId, $imageCollection, CACHE_TIME_DAY);
            $cachedAlbumKeys = $memcached->Get(MKEY_ALBUM_CACHE_KEYS_BY_USER.$userId);
            if ($cachedAlbumKeys === false) {
                $cachedAlbumKeys = array();
            }
            $cachedAlbumKeys[] = MKEY_IMAGES_BY_ALBUM_ID.$albumId;
            $memcached->Set(MKEY_ALBUM_CACHE_KEYS_BY_USER.$userId, $cachedAlbumKeys, CACHE_TIME_DAY);
        }

        return $imageCollection;
    }

    public function 
    AddNewImage(
       $userId, 
       $filename)
    {
        $sql = new Sql($this->getDbAdapter(), 'image');
        $insert = $sql->insert()
            ->columns(
                array(
                    'user_id',
                    'filename',
                )
            )
            ->values(
                array(
                    'user_id' => $userId,
                    'filename' => $filename,
                )
            );

        $statement = $sql->prepareStatementForSqlObject($insert);
        $newId = $statement->execute()->getGeneratedValue();

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
        $sql = new Sql($this->getDbAdapter()); 
        $delete = $sql->delete()
            ->from('album_content')
            ->where(
                array(
                    'image_id' => $imageId,
                )
            );
        
        $statement = $sql->prepareStatementForSqlObject($delete);
        $statement->execute();

        // Now delete the image
        $delete = $sql->delete()
            ->from('image')
            ->where(
                array (
                    'user_id' => $userId,
                    'id'      => $imageId,
                )
            );

        $statement = $sql->prepareStatementForSqlObject($delete);
        $statement->execute();

        // image deleted, clear caches
        $memcached = $this->GetMemcachedManager();
        $memcached->Delete(MKEY_IMAGES_BY_USER_ID.$userId);
        // get the keys of albums that have been stored
        $albumKeys = $memcached->Get(MKEY_ALBUM_CACHE_KEYS_BY_USER.$userId);
        $memcached->Delete($albumKeys);

        $memcached->Delete(MKEY_ALBUMS_BY_USER_ID.$userId);
    }
    
}
