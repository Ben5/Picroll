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
        $userId = $_SESSION['user_id'];

        $albumModel = new AlbumModel($this->getMemcachedManager());
        $imageModel = new ImageModel();

        $userAlbums = $albumModel->GetAllAlbumsByUserId($userId);

        if ($userAlbums !== false) {
            foreach ($userAlbums as &$album) {
                $album['images'] = $imageModel->GetAllImagesByAlbumId($album['id']);
            }
        }

        // Add a pseudo-album for 'All Images'
        $allImages = $imageModel->GetAllImagesByUserId($userId);
        $userAlbums[] = array(
            'id' => -1,
            'name' => 'All Pictures',
            'date_created' => -1, // TODO: figure this out if needed...
            'size' => count($allImages),
            'images' => $allImages,
        );

        $this->SetViewName('viewalbums');

        $this->ExposeVariable('imageBase', '/picroll/images/uploads/');
        $this->ExposeVariable('imageExt', '.jpeg');
        $this->ExposeVariable('albums', $userAlbums);
    }

    protected function
    ViewAllImages($params)
    {
        $userId = $_SESSION['user_id'];

        $imageModel = new ImageModel();
        $userImages = $imageModel->GetAllImagesByUserId($userId);

        $albumModel = new AlbumModel($this->getMemcachedManager());
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
    RemoveImagesFromAlbum($params)
    {
        $imageIds = $params['imageIds'];
        $albumId  = $params['albumId'];

        $albumModel = new AlbumModel($this->getMemcachedManager());
        $albumModel->RemoveImages($albumId, $imageIds);
    }

    protected function
    DeleteAlbums($params)
    {
        $userId   = $_SESSION['user_id'];
        $albumIds = $params['albumIds'];

        $albumModel = new AlbumModel($this->getMemcachedManager());

        foreach ($albumIds as $albumId) {
            $albumModel->DeleteAlbum($userId, $albumId);
        }
    }

    protected function
    NewAlbum($params)
    {
        $userId         = $_SESSION['user_id'];
        $albumName      = $params['albumName'];
        $pictureIdArray = $params['pictureIds'];

        $albumModel = new AlbumModel($this->getMemcachedManager());
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

        $albumModel = new AlbumModel($this->getMemcachedManager());
        $albumModel->AddContentToAlbum($albumId, $pictureIdArray);
    }

    protected function
    GetAlbumContents($params) 
    {
        $userId  = $_SESSION['user_id'];
        $albumId = $params['albumId'];

        $imageModel = new ImageModel();
        if ($albumId == -1) {
            $imagesInAlbum = $imageModel->GetAllImagesByUserId($userId);
        } else {
            $imagesInAlbum = $imageModel->GetAllImagesByAlbumId($albumId);
        }

        $this->ExposeVariable('images', $imagesInAlbum);
    }
}
