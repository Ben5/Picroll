<?php

namespace Site\Models;

use Picroll\SiteConfig;
use Reverb\System\ModelBase;

class User 
    extends ModelBase
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

        $query = $this->GetDbConnection()->NewQuery($sql);

        $query->AddStringParam($username);

        return $query->TryReadSingleRow();
    }

    public function
    SearchUsersByNameOrEmail($name)
    {
        $localUserId = $_SESSION['user_id'];

        // TODO: this still isnt perfect - it doesnt filter out pending requests. 
        // TODO: replace with 3 seperate queries to get friends, requests, and name matches.
        $sql = 'SELECT user.id, user.username
                FROM   user 
                WHERE user.id != ? 
                AND   (username LIKE ?  OR email LIKE ?)';

        $query = $this->GetDbConnection()->NewQuery($sql);

        $query->AddIntegerParam($localUserId); // don't return the local user.
        $query->AddStringParam('%'.$name.'%'); // username gets fully wildcarded
        $query->AddStringParam($name.'%'); // email only gets semi-wildcarded, to stop people searching for everone with a certain domain (or '.com')

        return $query->TryReadDictionary();
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

        $query = $this->GetDbConnection()->NewQuery($sql);
        $query->AddStringParam($username);
        $query->AddStringParam($email);
        $query->AddStringParam($salt);
        $query->AddStringParam($hashedPassword);

        return $query->TryExecuteInsert();
    }
}
