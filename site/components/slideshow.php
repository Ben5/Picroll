<?php

namespace Site\Components;

use Site\Config\SiteConfig;
use Reverb\System\ComponentBase;

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
        $albumId = isset($params['albumId']) ? $params['albumId'] : null;

        $imageModel = $this->GetDependencyContainer()->GetInstance('ImageModel');
        if (is_null($albumId)) {
            $allImages = $imageModel->GetAllImagesByUserId($userId);
        } else {
            $allImages = $imageModel->GetAllImagesByAlbumId($albumId, $userId);
        }
    

        $this->ExposeVariable('imageBase', '/picroll/images/uploads/');
        $this->ExposeVariable('imageExt', '.jpeg');
        $this->ExposeVariable('allImages', $allImages);
    }
}
