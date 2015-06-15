<?php

namespace Site\Models\Entities;

use Reverb\System\EntityBase;

class ImageEntity extends EntityBase
{
    private $id;
    private $filename;

    public function GetId()
    {
        return $this->id;
    }

    public function SetId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function GetFileName()
    {
        return $this->filename;
    }

    public function SetFileName($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    // EntityBase abstract function
    public function SetFromRow(array $row)
    {
        $this->SetId($row['id']);
        $this->SetFileName($row['filename']);
    }

    // EntityBase abstract function
    public function ToArray()
    {
        return array(
            'id'       => $this->id,
            'filename' => $this->filename,
        );
    }
}
