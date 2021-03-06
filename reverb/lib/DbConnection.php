<?php

namespace Reverb\Lib;

use Site\Config\SiteConfig;

class DbConnection
{
    private $connection;

    public function Connect()
    {
        $conn = new \mysqli( SiteConfig::DB_HOST, SiteConfig::DB_USER, SiteConfig::DB_PASS, SiteConfig::DB_DB);
    
        if ($conn === false) {
            trigger_error("no db connection!");
        }

        return $conn;
    }
   

    public function NewQuery($sql)
    {
        if (!$this->connection) {
           $this->connection = $this->Connect();
        }

        return new Query($sql, $this->connection); 
    }
}

class Query
{
    private $stmt;
    private $result;
    private $params = array();

    public function __construct($sql, $conn)
    {
        $this->stmt = $conn->Prepare($sql); 
        if ($this->stmt === false) {
            trigger_error("failed to prepare stmt for sql: $sql, error was: ".$conn->error);
        }
    }

    private function GetParamTypes()
    {
        $typeString = "";

        foreach ($this->params as $param) {
            $typeString .= $param['type'];
        }

        return $typeString;
    }

    public function AddIntegerParam($param)
    {
        $this->params[] = array("type" => "i", "value" => $param);
    }
    
    public function AddStringParam($param)
    {
        $this->params[] = array("type" => "s", "value" => $param);
    }

    public function AddDecimalParam($param)
    {
        $this->params[] = array("type" => "d", "value" => $param);
    }

    public function GetLastError()
    {
        return $this->stmt->error;
    }

    private function TryQuery()
    {
        // bind all params
        $paramsArray = array();
        $paramsArray[] = $this->GetParamTypes();

        $params = $this->params;
            
        if (count($params) > 0) {
            for ($i = 0; $i < count($params); $i++) {
                $bindName = "param".$i;
                $$bindName = $params[$i]['value'];
                $paramsArray[] =& $$bindName;
            }

            call_user_func_array(array($this->stmt, "bind_param"), $paramsArray);
        }

        // execute the query
        $success = $this->stmt->execute();
        return $success;
    }

    public function ExecuteInsert($errorMsg)
    {
        if ($this->TryExecuteInsert() === false) {
            trigger_error($errorMsg . " - mysql error: " .$this->GetLastError());
        }
    }

    public function TryExecuteInsert()
    {
        if ($this->TryQuery()) {
            return $this->stmt->insert_id;
        }
        return false;
    }

    public function ExecuteDelete($errorMsg)
    {
        if (!$this->TryExecuteDelete()) {
            trigger_error($errorMsg . " - mysql error: " .$this->GetLastError());
        }
    }

    public function TryExecuteDelete()
    {
        return $this->TryQuery();
    }

    public function TryReadSingleValue()
    {
        if ($this->TryQuery()) {
            $this->result = $this->stmt->get_result();
            $row = $this->result->fetch_array(MYSQLI_NUM);

            return $row[0];
        }

        return false;
    } 

    public function TryReadSingleRow()
    {
        if ($this->TryQuery()) {
            $this->result = $this->stmt->get_result();
            $row = $this->result->fetch_assoc();

            return $row;
        }

        return false;
    } 

    public function TryReadSingleColumn()
    {
        if ($this->TryQuery()) {
            $this->result = $this->stmt->get_result();

            $colArray = array();
            while ($row = $this->result->fetch_array()) {
                $colArray[] = $row[0];
            }

            return $colArray;
        }

        return false;
    } 

    public function TryReadDictionary()
    {
        if ($this->TryQuery()) {
            $this->result = $this->stmt->get_result();

            $dictionary = array();

            while ($row = $this->result->fetch_array(MYSQLI_NUM)) {
                if (count($row) !== 2) {
                    return false;
                }

                $dictionary[$row[0]] = $row[1];
            }

            return $dictionary;
        }

        return false;
    } 

    public function TryReadRowArray()
    {
        if ($this->TryQuery()) {
            $this->result = $this->stmt->get_result();

            $rowArray = $this->result->fetch_all(MYSQLI_ASSOC);

            return $rowArray;
        }

        return false;
    } 
}
