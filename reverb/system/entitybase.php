<?php

namespace Reverb\System;

abstract class EntityBase
{
    public function __construct($row = null)
    {
        if (!is_null($row)) {
            $this->SetFromRow($row);
        }
    }

    abstract public function SetFromRow(array $row);

    abstract public function ToArray();
}
