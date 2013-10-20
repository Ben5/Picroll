<?php

use Picroll\SiteConfig;

require_once SiteConfig::REVERB_ROOT."/system/modelbase.php";
require_once SiteConfig::REVERB_ROOT."/lib/DbInterface.php";

class AlbumModel extends ModelBase
{
    public function __construct()
    {
        $this->modelName = "album";
    }

    public function GetAllAlbumsByUserId($userId)
    {
        $sql = 'SELECT id, name, date_created
                FROM   album
                WHERE  user_id = ?';

        $query = DbInterface::NewQuery($sql);

        $query->AddStringParam($userId);

        return $query->TryReadAllRows();
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

        return $query->TryExecuteInsert();
    }

    public function AddContentToAlbum(
        $albumId,
        $imageId)
    {
        $sql = 'INSERT INTO album_content
                (album_id, image_id)
                VALUES
                (?, ?)';
        $query = DbInterface::NewQuery($sql);
        $query->AddIntegerParam($albumId);
        $query->AddIntegerParam($imageId);

        return $query->TryExecuteInsert();
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
    }
    
}