<?php

namespace Site\Components;

use Reverb\System\ComponentBase;
use Site\Models\Files\ImageFileHandler;
use Site\Models\ImageModel;
use Site\Models\ExifHandler;
use Site\Models\Files\FileWriter;
use Site\Models\Service\ImageModelAwareInterface;

class Upload extends ComponentBase
    implements ImageModelAwareInterface
{
    private $imageModel = null;

    const UPLOADED_IMAGE_DIR = '/opt/git/Picroll/site/images/uploads/';

    public function SetImageModel(ImageModel $instance)
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
        // default page, do nothing.
    }

    protected function UploadFile($params)
    {
        $userId = $_SESSION['user_id'];

        // TODO: process exif data and get name and stuff
        $uploadedEXIF = $_POST['exif'];
        // todo: inject this dependency!
        $exifHandler = new ExifHandler();
        $exifHandler->SetFromJsonString($uploadedEXIF);
        die('ded');

        // Write the file out to disk
        $uploadedImageData = $_POST['uploadImage'];
        // todo: inject this dependency!
        $imageHandler = new ImageFileHandler();
        $image = $imageHandler->ConvertDataUrl($uploadedImageData);

        $path = self::UPLOADED_IMAGE_DIR;
        $filename = md5($userId.time());

        // todo: inject this dependency!
        $fileWriter = new FileWriter();
        $fileWriter->WriteFileToDisk($image, $path, $filename);

        // Create a thumbnail version
        $thumbnail = $imageHandler->GenerateThumbnailFromFile($path.$filename.'.jpeg');
        $fileWriter->WriteFileToDisk($thumbnail, $path, $filename.'-thumb', 'w', '.jpeg');

        // Add the new files to the db
        $imageModel = $this->GetImageModel();
        $imageModel->AddNewImage($userId, $filename);

        $this->ExposeVariable('uploaded', true);
    }
}
