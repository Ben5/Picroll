<?php
/**
 * Created by PhpStorm.
 * User: bensmith
 * Date: 14/07/15
 * Time: 13:15
 */

namespace Site\Components\Service;

use Reverb\System\DependencyContainer;
use Site\Components\View;

class ViewFactory
{
    public function CreateInstance(DependencyContainer $dependencyContainer)
    {
        $albumModel = $dependencyContainer->GetInstance('AlbumModel');
        $imageModel = $dependencyContainer->GetInstance('ImageModel');

        return new View($albumModel, $imageModel);
    }
}