<?php

namespace Reverb\Lib;

use Reverb\System\EntityBase;
use Zend\Db\Adapter\Driver\ResultInterface;

class Collection
{
    private $items;

    public function __construct(EntityBase $entityType = null, ResultInterface $dbResultSet = null)
    {
        if (!is_null($entityType) && !is_null($dbResultSet)) {
            foreach ($dbResultSet as $row) {
                $entity = clone($entityType);
                $entity->SetFromRow($row);
                $this->AddItem($entity);
            }
        }
    }

    public function GetItems()
    {
        return $this->items;
    }

    public function AddItem($item)
    {
        $this->items[] = $item;
    }

    public function ToArray()
    {
        $outputArray = array();
        foreach ($this->items as $item) {
            $outputArray[] = $item->ToArray();
        }
        return $outputArray;
    }
}

