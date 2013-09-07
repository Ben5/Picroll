<?php

use Picroll\SiteConfig;

require_once SiteConfig::REVERB_ROOT."/system/modelbase.php";
require_once SiteConfig::REVERB_ROOT."/lib/DbInterface.php";

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
        $sql = 'SELECT filename
                FROM   image
                WHERE  user_id = ?';

        $query = DbInterface::NewQuery($sql);

        $query->AddStringParam($userId);

        return $query->TryReadSingleColumn();
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
}
