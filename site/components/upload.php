<?php

use Picroll\SiteConfig;

include(SiteConfig::REVERB_ROOT."/system/componentbase.php");
include(SiteConfig::SITE_ROOT."/models/image.php");

class Upload extends ComponentBase
{
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
    
        if(!is_dir($path))
        {
            mkdir($path);
        }
        $filename = md5($userId.time());

        $file = fopen($path.$filename.'.jpeg', 'w');
        fwrite($file, $image);
        fclose($file);

        // Add the file to the db
        $imageModel = new ImageModel();
        $imageModel->AddNewImage($userId, $filename);

       // $this->ExposeVariable('data', $params['name']);
        $this->ExposeVariable('uploaded', true);
    }

    private function
    ConvertDataUrl($dataUrl)
    {
        // Assumes the data URL represents a jpeg image
        $image = base64_decode( str_replace('data:image/jpeg;base64,', '', $dataUrl) );
        return $image;
    }
}
