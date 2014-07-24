<?php

namespace Site\Components;

use Reverb\System\ComponentBase;
use Site\Config\SiteConfig;
use Site\Models\ImageModel;
use Site\Models\Service\ImageModelAwareInterface;

class Upload extends ComponentBase
    implements ImageModelAwareInterface
{
    private $imageModel = null;

    public function SetImageModel(ImageModel $instance)
    {
        $this->imageModel = $instance;
    }

    public function GetImageModel()
    {
        return $this->imageModel;
    }

    protected function
    RequiresAuthentication()
    {
        return true;
    }

    protected function
    Index($params)
    {
    }

    protected function
    UploadFile($params)
    {
        $userId = $_SESSION['user_id'];

        // TODO: process exif data and get name and stuff
        $uploadedEXIF = $_POST['exif'];

        // Write the file out to disk
        $uploadedImageData = $_POST['uploadImage'];
        $image = $this->ConvertDataUrl($uploadedImageData);

        $path = '/opt/git/Picroll/site/images/uploads/';
    
        if (!is_dir($path)) {
            mkdir($path);
        }
        $filename = md5($userId.time());

        $file = fopen($path.$filename.'.jpeg', 'w');
        fwrite($file, $image);
        fclose($file);

        // Create a thumbnail version
        $thumbnail = new \Imagick($path.$filename.'.jpeg');
        $thumbnail->thumbnailImage(160, 0);

        $file = fopen($path.$filename.'-thumb.jpeg', 'w');
        fwrite($file, $thumbnail);
        fclose($file);

        // Add the new files to the db
        $imageModel = $this->GetImageModel();
        $imageModel->AddNewImage($userId, $filename);

        // $this->ExposeVariable('data', $params['name']);
        $this->ExposeVariable('uploaded', true);
    }

    private function
    ConvertDataUrl($dataUrl)
    {
        // Assumes the data URL represents a jpeg image
        $image = base64_decode(str_replace('data:image/jpeg;base64,', '', $dataUrl));
        return $image;
    }
}
