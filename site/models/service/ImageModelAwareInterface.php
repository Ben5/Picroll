<?php

namespace Site\Models\Service;

use Site\Models\ImageModel;

interface ImageModelAwareInterface
{
    public function GetImageModel();
    public function SetImageModel(ImageModel $instance);
}
