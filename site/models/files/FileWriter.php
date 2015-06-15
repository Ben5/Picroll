<?php
/**
 * Created by PhpStorm.
 * User: bensmith
 * Date: 15/06/15
 * Time: 13:02
 */

namespace Site\Models\Files;

class FileWriter {

    public function WriteFileToDisk($contents, $targetDir, $filename, $readWriteOption = 'w', $fileExt = '.jpeg')
    {
        $filename .= $fileExt;

        if (!is_dir($targetDir)) {
            mkdir($targetDir);
        }

        $file = fopen($targetDir.$filename, $readWriteOption);
        fwrite($file, $contents);
        fclose($file);
    }
}