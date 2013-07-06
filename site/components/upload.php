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

    protected function
    UploadFile($params)
    {
        $allowedExts  = array('png', 'jpg', 'jpeg');
        $allowedTypes = array('image/png', 'image/jpeg', 'image/gif', 'image/jpg', 'image/pjpeg', 'image/x-png');
        
        $uploadedImage = $_FILES['uploadImage'];

        $temp = explode(".", $uploadedImage["name"]);
        $extension = end($temp);

        if ( in_array(strtolower($extension), $allowedExts) )
        {
            if( in_array($uploadedImage['type'], $allowedTypes) )
            {
                if (file_exists('/opt/git/Picroll/site/images/uploads/' . $uploadedImage['name'] ))
                {
                    die('filename exists ('.$uploadedImage['name'].')');
                }
                else
                {
                    $success = move_uploaded_file($uploadedImage['tmp_name'], '/opt/git/Picroll/site/images/uploads/' . $uploadedImage['name']);
                    if (!$success)
                    {
                        die('Couldn\'t move file');
                    }
                }
            }
            else
            {
                die('Invalid Type: '.$uploadedImage['type']);
            }
        }
        else
        {
            die('Invalid Extension: '.$extension);
        }


       // $this->ExposeVariable('data', $params['name']);
        $this->ExposeVariable('uploaded', true);
    }
}
