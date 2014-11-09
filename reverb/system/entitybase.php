<?php

namespace Reverb\System;

abstract class EntityBase implements EntityInterface
{
    public function __construct($row = null)
    {
        if (!is_null($row)) {
            $this->SetFromRow($row);
        }
    }
}
