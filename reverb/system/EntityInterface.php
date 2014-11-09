<?php

namespace Reverb\System;

interface EntityInterface
{
    public function SetFromRow(array $row);

    public function ToArray();
}
