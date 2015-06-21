<?php
/**
 * Created by PhpStorm.
 * User: bensmith
 * Date: 20/06/15
 * Time: 23:28
 */

namespace Site\Models;

class ExifHandler
{
    protected $exifData = array();

    public function __construct()
    {

    }

    public function SetFromJsonString($exifString)
    {
        $this->exifData = (array)json_decode($exifString);
    }
}