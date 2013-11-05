<?php

use Picroll\SiteConfig;

require_once SiteConfig::REVERB_ROOT."/system/modelbase.php";

class ImageModel extends ModelBase
{
    public function
    __construct()
    {
        $this->modelName = "image";
    }

    public function 
    GetAllImagesByUserId($userId)
    {
        $sql = 'SELECT id, filename
                FROM   image
                WHERE  user_id = ?';

        $query = DbInterface::NewQuery($sql);

        $query->AddStringParam($userId);

        return $query->TryReadDictionary();
    }

    public function 
    GetAllImagesByAlbumId($albumId)
    {
        $sql = 'SELECT image.id, filename
                FROM   image
                JOIN   album_content ON album_content.image_id = image.id
                WHERE  album_content.album_id = ?';

        $query = DbInterface::NewQuery($sql);

        $query->AddIntegerParam($albumId);

        return $query->TryReadDictionary();
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

        return $query->TryExecuteInsert();
    }

    public function 
    DeleteImage(
        $userId, 
        $imageId)
    {
        $sql = 'DELETE FROM image 
                WHERE user_id = ?
                AND   id = ?';
        $query = DbInterface::NewQuery($sql);
        $query->AddIntegerParam($userId);
        $query->AddIntegerParam($imageId);

        $query->ExecuteDelete('Unable to delete image');
    }
    
}
