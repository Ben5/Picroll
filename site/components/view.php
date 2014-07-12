<?php

namespace Site\Components;

use Picroll\SiteConfig;
use Reverb\System\ComponentBase;

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
        $userId     = $_SESSION['user_id'];

        $albumModel = $this->GetDependencyContainer()->GetInstance('AlbumModel');
        $imageModel = $this->GetDependencyContainer()->GetInstance('ImageModel');

        $userAlbums = $albumModel->GetAllAlbumsByUserId($userId);

        if ($userAlbums !== false) {
            foreach ($userAlbums as &$album) {
                $album['images'] = $imageModel->GetAllImagesByAlbumId($album['id'], $userId);
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
        $userId     = $_SESSION['user_id'];

        $imageModel = $this->GetDependencyContainer()->GetInstance('ImageModel');
        $userImages = $imageModel->GetAllImagesByUserId($userId);

        $albumModel = $this->GetDependencyContainer()->GetInstance('AlbumModel');
        $userAlbums = $albumModel->GetAllAlbumsByUserId($userId);

        $this->ExposeVariable('imageBase', '/picroll/images/uploads/');
        $this->ExposeVariable('imageExt', '.jpeg');
        $this->ExposeVariable('albums', $userAlbums);
        $this->ExposeVariable('images', $userImages);
    }

    protected function
    DeleteImages($params)
    {
        $userId     = $_SESSION['user_id'];
        $imageIds   = $params['imageIds'];

        $imageModel = $this->GetDependencyContainer()->GetInstance('ImageModel');

        foreach ($imageIds as $imageId) {
            $imageModel->DeleteImage($userId, $imageId);
        }
    }

    protected function
    RemoveImagesFromAlbum($params)
    {
        $userId     = $_SESSION['user_id'];
        $imageIds   = $params['imageIds'];
        $albumId    = $params['albumId'];

        $albumModel = $this->GetDependencyContainer()->GetInstance('AlbumModel');

        $albumModel->RemoveImages($albumId, $imageIds, $userId);
    }

    protected function
    DeleteAlbums($params)
    {
        $userId     = $_SESSION['user_id'];
        $albumIds   = $params['albumIds'];

        $albumModel = $this->GetDependencyContainer()->GetInstance('AlbumModel');

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

        $albumModel     = $this->GetDependencyContainer()->GetInstance('AlbumModel');
        $newAlbumId     = $albumModel->AddNewAlbum($userId, $albumName);

        if ($newAlbumId !== false) {
            $albumModel->AddContentToAlbum($newAlbumId, $pictureIdArray, $userId);
        }

        $this->ExposeVariable('newAlbumId', $newAlbumId);
    }

    protected function
    AddToAlbum($params)
    {
        $userId         = $_SESSION['user_id'];
        $albumId        = $params['albumId'];
        $pictureIdArray = $params['pictureIds'];

        $albumModel     = $this->GetDependencyContainer()->GetInstance('AlbumModel');
        
        $albumModel->AddContentToAlbum($albumId, $pictureIdArray, $userId);
    }

    protected function
    GetAlbumContents($params) 
    {
        $userId     = $_SESSION['user_id'];
        $albumId    = $params['albumId'];

        $imageModel = $this->GetDependencyContainer()->GetInstance('ImageModel');

        if ($albumId == -1) {
            $imagesInAlbum = $imageModel->GetAllImagesByUserId($userId);
        } else {
            $imagesInAlbum = $imageModel->GetAllImagesByAlbumId($albumId, $userId);
        }

        $this->ExposeVariable('images', $imagesInAlbum);
    }
}
