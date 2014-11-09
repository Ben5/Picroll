<?php

namespace Site\Models\Entities;

use Reverb\System\EntityBase;

class AlbumEntity extends EntityBase
{
    private $id;
    private $name;
    private $dateCreated;
    private $size;
    private $coverImageId;
    private $coverImageFilename;


    public function GetId()
    {
        return $this->id;
    }

    public function SetId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function GetName()
    {
        return $this->name;
    }

    public function SetName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function GetDateCreated()
    {
        return $this->dateCreated;
    }

    public function SetDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    public function GetSize()
    {
        return $this->size;
    }

    public function SetSize($size)
    {
        $this->size = $size;
        return $this;
    }

    public function GetCoverImageId()
    {
        return $this->coverImageId;
    }

    public function SetCoverImageId($coverImageId)
    {
        $this->coverImageId = $coverImageId;
        return $this;
    }

    public function GetCoverImageFilename()
    {
        return $this->coverImageFilename;
    }

    public function SetCoverImageFilename($coverImageFilename)
    {
        $this->coverImageFilename = $coverImageFilename;
        return $this;
    }

    // EntityBase abstract function
    public function SetFromRow(array $row)
    {
        $this->SetId($row['id']);
        $this->SetName($row['name']);
        $this->SetDateCreated($row['date_created']);
        $this->SetSize($row['size']);
        $this->SetCoverImageId($row['cover_image_id']);
        $this->SetCoverImageFilename($row['cover_image_filename']);
    }

    // EntityBase abstract function
    public function ToArray()
    {
        return array(
            'id'                    => $this->id,
            'name'                  => $this->name,
            'date_created'          => $this->dateCreated,
            'size'                  => $this->size,
            'cover_image_id'        => $this->coverImageId,
            'cover_image_filename'  => $this->coverImageFilename,
        );
    }
}
