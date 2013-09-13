<?php

use Picroll\SiteConfig;

require_once SiteConfig::REVERB_ROOT."/system/componentbase.php";
require_once SiteConfig::SITE_ROOT."/models/image.php";

class Slideshow extends ComponentBase
{
    protected function
    RequiresAuthentication()
    {
        return true;
    }

    protected function
    Index($params)
    {
        $userId = $_SESSION['user_id'];

        // Get all images for the local user (TODO: make this a specific set of related images!)
        $imageModel = new ImageModel();
        $allImages = $imageModel->GetAllImagesByUserId($userId);

        // trim it to 10 images for now
        //$allImages = array_slice($allImages, 0, 10);

        $this->ExposeVariable('imageBase', '/picroll/images/uploads/');
        $this->ExposeVariable('imageExt', '.jpeg');
        $this->ExposeVariable('allImages', $allImages);
    }
}
