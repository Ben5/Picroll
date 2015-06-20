<?php

namespace Site\Components;

use Reverb\System\ComponentBase;
use Site\Config\SiteConfig;
use Site\Models\ImageModel;
use Site\Models\Service\ImageModelAwareInterface;

class Slideshow extends ComponentBase
    implements ImageModelAwareInterface
{
    private $imageModel;

    public function GetImageModel()
    {
        return $this->imageModel;
    }

    public function SetImageModel(ImageModel $instance)
    {
        $this->imageModel = $instance;
    }

    protected function RequiresAuthentication()
    {
        return true;
    }

    protected function Index($params)
    {
        $userId = $_SESSION['user_id'];
        $albumId = isset($params['albumId']) ? $params['albumId'] : null;

        $imageModel = $this->GetImageModel();
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
