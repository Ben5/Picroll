<?php

namespace Site\Models;

use Site\Config\SiteConfig;
use Reverb\System\ModelBase;

class FriendModel 
    extends ModelBase
{
    public function __construct()
    {
        $this->modelName = "friendship";
    }

    public function GetAllFriendsByUserId($userId)
    {
        $sql = 'SELECT f.friend_user_id, u.username
                FROM   friendship f
                JOIN user u ON u.id = f.friend_user_id
                WHERE  f.user_id = ?';

        $query = $this->GetDbConnection()->NewQuery($sql);

        $query->AddStringParam($userId);

        return $query->TryReadDictionary();
    }

    public function AddNewFriend($userId, $friendUserId)
    {
        // we add 2 friendships, so that we can simplfy searching for a user's friends.
        $sql = "INSERT INTO friendship (user_id, friend_user_id)
                VALUES (?, ?), (?, ?)";

        $query = $this->GetDbConnection()->NewQuery($sql);
        $query->AddIntegerParam($userId);
        $query->AddIntegerParam($friendUserId);
        $query->AddIntegerParam($friendUserId);
        $query->AddIntegerParam($userId);

        return $query->TryExecuteInsert('no inserty');
    }
}
