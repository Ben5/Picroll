<?php

use Picroll\SiteConfig;

include(SiteConfig::REVERB_ROOT."/system/componentbase.php");
include(SiteConfig::SITE_ROOT."/models/image.php");
include(SiteConfig::SITE_ROOT."/models/album.php");

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
        $userImages = $imageModel->GetAllImagesByUserId($userId);

        $albumModel = new AlbumModel();
        $userAlbums = $albumModel->GetAllAlbumsByUserId($userId);

        $this->ExposeVariable('imageBase', '/picroll/images/uploads/');
        $this->ExposeVariable('imageExt', '.jpeg');
        $this->ExposeVariable('albums', $userAlbums);
        $this->ExposeVariable('images', $userImages);
    }

    protected function
    DeleteImages($params)
    {
        $userId   = $_SESSION['user_id'];
        $imageIds = $params['imageIds'];

        $imageModel = new ImageModel();

        foreach ($imageIds as $imageId) {
            $imageModel->DeleteImage($userId, $imageId);
        }
    }

    protected function
    NewAlbum($params)
    {
        $userId         = $_SESSION['user_id'];
        $albumName      = $params['albumName'];
        $pictureIdArray = $params['pictureIds'];

        $albumModel = new AlbumModel();
        $newAlbumId = $albumModel->AddNewAlbum($userId, $albumName);

        if ($newAlbumId !== false) {
            $albumModel->AddContentToAlbum($newAlbumId, $pictureIdArray);
        }

        $this->ExposeVariable('newAlbumId', $newAlbumId);
    }

    protected function
    AddToAlbum($params)
    {
        $userId         = $_SESSION['user_id'];
        $albumId        = $params['albumId'];
        $pictureIdArray = $params['pictureIds'];

        $albumModel = new AlbumModel();
        $albumModel->AddContentToAlbum($albumId, $pictureIdArray);
    }
}
