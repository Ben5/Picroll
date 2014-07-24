<?php

namespace Reverb\System;

use Site\Config\SiteConfig;
use Reverb\Lib\DbConnection;
use Reverb\Lib\DbConnectionAwareInterface;
use Reverb\Lib\DbAdapterAwareInterface;
use \Zend\Db\Adapter\Adapter;

abstract class ModelBase implements DbConnectionAwareInterface, DbAdapterAwareInterface
{
    protected $modelName = "";
    protected $dbConnection;
    protected $dbAdapter;

    public final function GetDbConnection()
    {
        return $this->dbConnection;
    }

    public final function SetDbConnection(DbConnection $instance)
    {
        $this->dbConnection = $instance;
    }

    public final function GetDbAdapter()
    {
        return $this->dbAdapter;
    }

    public final function SetDbAdapter(Adapter $instance)
    {
        $this->dbAdapter = $instance;
    }

    public final function GetAll()
    {
        $sql = "SELECT * FROM " . $this->modelName;
        $query = $this->GetDbConnection()->NewQuery($sql);

        return $query->TryReadRowArray();
    }

    public final function GetOneById($id)
    {
        $sql = "SELECT * FROM ? WHERE id = ?";
        $query = $this->GetDbConnection()->NewQuery($sql);

        $query->AddStringParam($this->modelName);
        $query->AddIntegerParam($id);

        return $query->TryReadSingleRow();
    }

    public final function GetEnumValues($columnName)
    {
        $sql = "SELECT COLUMN_TYPE
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = ?
                AND COLUMN_NAME = ?";
        $query = $this->GetDbConnection()->NewQuery($sql);
        $query->AddStringParam($this->modelName);
        $query->AddStringParam($columnName);

        $result = $query->TryReadSingleValue();

        $trimmedResult = str_replace(array('enum(', '\'', ')'), '', $result);
        $resultArray = explode(',', $trimmedResult);
        return $resultArray;
    }
}
