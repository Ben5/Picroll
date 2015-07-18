<?php

namespace Site\Components;

use Reverb\System\ComponentBase;
use Site\Config\SiteConfig;
use Site\Models\ImageModel;

class Slideshow extends ComponentBase
{
    private $imageModel;

    public function __construct(ImageModel $instance)
    {
        $this->imageModel = $instance;
    }

    public function GetImageModel()
    {
        return $this->imageModel;
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
