<?php

abstract class ModelBase
{
    protected $modelName = "";

    public final function
    GetAll()
    {
        $sql = "SELECT * FROM " . $this->modelName;
        $query = DbInterface::NewQuery($sql);

        return $query->TryReadRowArray();
    }

    public final function
    GetOneById($id)
    {
        $sql = "SELECT * FROM ? WHERE id = ?";
        $query = DbInterface::NewQuery($sql);

        $query->AddStringParam($this->modelName);
        $query->AddIntegerParam($id);

        return $query->TryReadSingleRow();
    }

    public final function
    GetEnumValues($columnName)
    {
        $sql = "SELECT COLUMN_TYPE
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = ?
                AND COLUMN_NAME = ?";
        $query = DbInterface::NewQuery($sql);
        $query->AddStringParam($this->modelName);
        $query->AddStringParam($columnName);

        $result = $query->TryReadSingleValue();

        $trimmedResult = str_replace(array('enum(', '\'', ')'), '', $result);
        $resultArray = explode(',', $trimmedResult);
        return $resultArray;
    }

}
