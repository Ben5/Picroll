<?php
/**
 * Created by PhpStorm.
 * User: bensmith
 * Date: 14/07/15
 * Time: 13:15
 */

namespace Site\Components\Service;

use Reverb\System\DependencyContainer;
use Site\Components\Upload;

class UploadFactory
{
    public function CreateInstance(DependencyContainer $dependencyContainer)
    {
        $imageModel = $dependencyContainer->GetInstance('ImageModel');

        return new Upload($imageModel);
    }
}