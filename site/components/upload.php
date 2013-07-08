<?php

use Picroll\SiteConfig;

include(SiteConfig::REVERB_ROOT."/system/componentbase.php");

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
        $this->ExposeVariable("msg", "Hello everybody!"); 
    }

    private function
    ConvertDataUrl($dataUrl)
    {
        // Assumes the data URL represents a jpeg image
        $image = base64_decode( str_replace('data:image/jpeg;base64,', '', $dataUrl) );
        return $image;
    }

    protected function
    UploadFile($params)
    {
        $allowedExts  = array('png', 'jpg', 'jpeg');
        $allowedTypes = array('image/png', 'image/jpeg', 'image/gif', 'image/jpg', 'image/pjpeg', 'image/x-png');
        
        $uploadedEXIF  = $_POST['exif'];
        // process exif data and get name and stuff


        $uploadedImage = $_POST['uploadImage'];
        $image = $this->ConvertDataUrl($uploadedImage);
        $path = '/opt/git/Picroll/site/images/uploads/';
        $filename = 'test.jpeg';
        $file = fopen($path.$filename, 'w');
        fwrite($file, $image);
        fclose($file);


       // $this->ExposeVariable('data', $params['name']);
        $this->ExposeVariable('uploaded', true);
    }
}
