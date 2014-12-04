<?php

namespace Site\Models;

use Reverb\Lib\MemcachedManager;
use Reverb\Lib\MemcachedManagerAwareInterface;
use Reverb\Lib\Collection;
use Reverb\System\ModelBase;
use Site\Config\SiteConfig;
use Site\Models\Entities\AlbumEntity;
use \Zend\Db\Sql\Expression;
use \Zend\Db\Sql\Sql;

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
        return $this->memcachedManager;
    }

    public function SetMemcachedManager(MemcachedManager $instance)
    {
        $this->memcachedManager = $instance;
    }

    public function GetAllAlbumsByUserId($userId)
    {
        $memcached = $this->GetMemcachedManager();

        $albumCollection = $memcached->get(MKEY_ALBUMS_BY_USER_ID.$userId);

        if ($albumCollection === false) {
            $sql = new Sql($this->getDbAdapter(), 'album');
            $select = $sql->select()
                ->columns(
                    array(
                        'id' => 'id',
                        'name' => 'name',
                        'date_created',
                        'size' => new Expression('COUNT(image_id)'),
                    )
                )
                ->join(
                    'album_content',
                    'album.id = album_content.album_id'
                )
                ->join(
                    'image',
                    'image.id = album_content.image_id',
                    array(
                        'cover_image_id' => 'id',
                        'cover_image_filename' => 'filename',
                    )
                )
                ->where(
                    array(
                        'album.user_id' => $userId,
                    )
                )
                ->group('album_content.album_id');

            $statement = $sql->prepareStatementForSqlObject($select);
            $resultSet = $statement->execute();

            $albumCollection = new Collection(new AlbumEntity(), $resultSet);

            $memcached->set(MKEY_ALBUMS_BY_USER_ID.$userId, $albumCollection, CACHE_TIME_DAY);
        }
        
        return $albumCollection;
    }

    public function AddNewAlbum(
       $userId, 
       $name)
    {
        $sql = new Sql($this->getDbAdapter(), 'album');
        $insert = $sql->insert()
            ->columns(
                array(
                    'user_id',
                    'name',
                )
            )
            ->values(
                array(
                    'user_id' => $userId,
                    'name'    => $name,
                )
            );

        $statement = $sql->prepareStatementForSqlObject($insert);
        $newId = $statement->execute()->getGeneratedValue();

        // added a new album, clear the old cached albums list
        $this->GetMemcachedManager()->Delete(MKEY_ALBUMS_BY_USER_ID.$userId);

        return $newId;
    }

    public function AddContentToAlbum(
        $albumId,
        array $imageIdArray,
        $userId)
    {
        $query = 'INSERT IGNORE INTO album_content (album_id, image_id) VALUES ';

        $queryVals = array();
        foreach ($imageIdArray as $imageId) {
            $queryVals[] = "($albumId, $imageId)";
        }

        $statement = $this->getDbAdapter()->query($query . implode(',', $queryVals));

        $newId = $statement->execute()->getGeneratedValue();

        if ($newId !== false) {
            // altered an album, clear the old cached albums list
            $this->GetMemcachedManager()->Delete(MKEY_ALBUMS_BY_USER_ID.$userId);
        }
        
        return $newId;
    }

    public function RemoveImages(
        $albumId,
        $imageIds,
        $userId)
    {
        $sql = new Sql($this->getDbAdapter(), 'album_content');
        $delete = $sql->delete()
            ->where(
                array(
                    'album_id' => $albumId,
                    'image_id' => $imageIds,
                )
            );

        $sql->prepareStatementForSqlObject($delete)->execute();

        // deleted from an album, clear the old cached albums list
        $this->GetMemcachedManager()->Delete(MKEY_ALBUMS_BY_USER_ID.$userId);
}

    public function DeleteAlbum(
        $userId, 
        $albumId)
    {
        // First delete the contents from the album
        $sql = new Sql($this->getDbAdapter(), 'album_content');
        $delete = $sql->delete()
            ->where(
                array(
                    'album_id' => $albumId,
                )
            );

        $sql->prepareStatementForSqlObject($delete)->execute();

        // Now delete the album itself
        $sql = new Sql($this->getDbAdapter(), 'album');
        $delete = $sql->delete()
            ->where(
                array(
                    'id'      => $albumId,
                    'user_id' => $userId,
                )
            );

        $sql->prepareStatementForSqlObject($delete)->execute();

        // deleted from an album, clear the old cached albums list
        $this->GetMemcachedManager()->Delete(MKEY_ALBUMS_BY_USER_ID.$userId);
    }
}
