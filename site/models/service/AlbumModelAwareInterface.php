<?php

namespace Site\Models\Service;

use Site\Models\AlbumModel;

interface AlbumModelAwareInterface
{
    public function GetAlbumModel();
    public function SetAlbumModel(AlbumModel $instance);
}
