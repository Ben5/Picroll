<?php

use Picroll\SiteConfig;

include(SiteConfig::REVERB_ROOT."/system/componentbase.php");
include(SiteConfig::SITE_ROOT."/models/image.php");

class View extends ComponentBase
{
    protected function
    RequiresAuthentication()
    {
        return true;
    }

    protected function 
    Index($params)
    {
        $this->ExposeVariable("msg", "Hello everybody!"); 
    }

    protected function
    ViewAllImages($params)
    {
        $userId = $_SESSION['user_id'];

        $imageModel = new ImageModel();
        $imagesForUser = $imageModel->GetAllImagesByUserId($userId);

        $this->ExposeVariable('imageBase', '/picroll/images/uploads/');
        $this->ExposeVariable('imageExt', '.jpeg');
        $this->ExposeVariable('images', $imagesForUser);
    }

    protected function
    DeleteImages($params)
    {
        $userId   = $_SESSION['user_id'];
        $imageIds = $_REQUEST['imageIds'];

        $imageModel = new ImageModel();

        foreach ($imageIds as $imageId) {
            $imageModel->DeleteImage($userId, $imageId);
        }
    }
}
