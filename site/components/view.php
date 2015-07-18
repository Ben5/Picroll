<?php

namespace Site\Components;

use Reverb\System\ComponentBase;
use Site\Config\SiteConfig;
use Site\Models\AlbumModel;
use Site\Models\ImageModel;
use Site\Models\Entities\AlbumEntity;

class View extends ComponentBase
{
    private $albumModel;
    private $imageModel;

    public function __construct(AlbumModel $albumModel, ImageModel $imageModel)
    {
        $this->albumModel = $albumModel;
        $this->imageModel = $imageModel;
    }

    public function GetAlbumModel() 
    {
        return $this->albumModel;
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
        $userId     = $_SESSION['user_id'];

        $albumModel = $this->GetAlbumModel();
        $imageModel = $this->GetImageModel();

        $userAlbums = $albumModel->GetAllAlbumsByUserId($userId);


        // Add a pseudo-album for 'All Images'
        $allImages = $imageModel->GetAllImagesByUserId($userId)->GetItems();
        // TODO: move the creation of All Pictures pseudo-array in to Album Model...
        $userAlbums->AddItem(new AlbumEntity(array(
            'id' => -1,
            'name' => 'All Pictures',
            'date_created' => -1, // TODO: figure this out if needed...
            'size' => count($allImages),
            'cover_image_id' => $allImages[0]->GetId(),
            'cover_image_filename' => $allImages[0]->GetFilename(),
        )));

        $this->SetViewName('viewalbums');

        $this->ExposeVariable('imageBase', '/picroll/images/uploads/');
        $this->ExposeVariable('imageExt', '.jpeg');
        $this->ExposeVariable('albums', $userAlbums->GetItems());
    }

    protected function DeleteImages($params)
    {
        $userId     = $_SESSION['user_id'];
        $imageIds   = $params['imageIds'];

        $imageModel = $this->GetImageModel();

        foreach ($imageIds as $imageId) {
            $imageModel->DeleteImage($userId, $imageId);
        }
    }

    protected function RemoveImagesFromAlbum($params)
    {
        $userId     = $_SESSION['user_id'];
        $imageIds   = $params['imageIds'];
        $albumId    = $params['albumId'];

        $albumModel = $this->GetAlbumModel();

        $albumModel->RemoveImages($albumId, $imageIds, $userId);
    }

    protected function DeleteAlbums($params)
    {
        $userId     = $_SESSION['user_id'];
        $albumIds   = $params['albumIds'];

        $albumModel = $this->GetAlbumModel();

        foreach ($albumIds as $albumId) {
            $albumModel->DeleteAlbum($userId, $albumId);
        }
    }

    protected function NewAlbum($params)
    {
        $userId         = $_SESSION['user_id'];
        $albumName      = $params['albumName'];
        $pictureIdArray = $params['pictureIds'];

        $albumModel     = $this->GetAlbumModel();
        $newAlbumId     = $albumModel->AddNewAlbum($userId, $albumName);

        if ($newAlbumId !== false) {
            $albumModel->AddContentToAlbum($newAlbumId, $pictureIdArray, $userId);
        }

        $this->ExposeVariable('newAlbumId', $newAlbumId);
    }

    protected function AddToAlbum($params)
    {
        $userId         = $_SESSION['user_id'];
        $albumId        = $params['albumId'];
        $pictureIdArray = $params['pictureIds'];

        $albumModel     = $this->GetAlbumModel();
        
        $albumModel->AddContentToAlbum($albumId, $pictureIdArray, $userId);
    }

    protected function GetAlbumContents($params)
    {
        $userId     = $_SESSION['user_id'];
        $albumId    = $params['albumId'];

        $imageModel = $this->GetImageModel();

        if ($albumId == -1) {
            $imagesInAlbum = $imageModel->GetAllImagesByUserId($userId);
        } else {
            $imagesInAlbum = $imageModel->GetAllImagesByAlbumId($albumId, $userId);
        }

        $this->ExposeVariable('images', $imagesInAlbum->ToArray());
    }
}
