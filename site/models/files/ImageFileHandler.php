<?php
/**
 * Created by PhpStorm.
 * User: bensmith
 * Date: 17/06/15
 * Time: 12:54
 */

namespace Site\Models\Files;


class ImageFileHandler
{
    public function ConvertDataUrl($dataUrl)
    {
        // Assumes the data URL represents a jpeg image
        $image = base64_decode(str_replace('data:image/jpeg;base64,', '', $dataUrl));
        return $image;
    }
}