<?php

use Reverb\SiteConfig;

require_once SiteConfig::REVERB_ROOT."/system/modelbase.php";
require_once SiteConfig::REVERB_ROOT."/lib/DbInterface.php";

class UserModel extends ModelBase
{
    public function
    __construct()
    {
       $this->modelName = "user";
    }

    public function
    TryGetUserByName($username)
    {
        $sql = 'SELECT id, username, email, generated_salt, password_hash
                FROM user
                WHERE username = ?';

        $query = DbInterface::NewQuery($sql);

        $query->AddStringParam($username);

        return $query->TryReadSingleRow();
    }

    public function
    AddNewUser(
       $username, 
       $salt,
       $hashedPassword, 
       $email)
    {
        $sql = "INSERT INTO user (username, email, generated_salt, password_hash)
                VALUES (?, ?, ?, ?)";

        $query = DbInterface::NewQuery($sql);
        $query->AddStringParam($username);
        $query->AddStringParam($email);
        $query->AddStringParam($salt);
        $query->AddStringParam($hashedPassword);

        return $query->TryExecuteInsert();
    }
}
