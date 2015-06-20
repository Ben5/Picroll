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
    const DEFAULT_THUMB_HEIGHT = 0;
    const DEFAULT_THUMB_WIDTH  = 160;

    public function __construct()
    {
        // nothing to do yet
    }

    public function ConvertDataUrl($dataUrl)
    {
        // Assumes the data URL represents a jpeg image
        $image = base64_decode(str_replace('data:image/jpeg;base64,', '', $dataUrl));
        return $image;
    }

    public function GenerateThumbnailFromFile(
        $filePath,
        $width = self::DEFAULT_THUMB_WIDTH,
        $height = self::DEFAULT_THUMB_HEIGHT)
    {
        $thumbnail = new \Imagick($filePath);
        $thumbnail->thumbnailImage($width, $height);

        return $thumbnail;
    }
}