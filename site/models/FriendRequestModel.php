<?php

namespace Site\Models;

use Site\Config\SiteConfig;
use Reverb\System\ModelBase;

class FriendRequestModel 
    extends ModelBase
{
    public function
    __construct()
    {
        $this->modelName = 'friend_request';
    }

    public function
    CreateNewFriendRequest($userId, $friendId)
    {
        // Use IGNORE here, so that duplicate key errors are silently ignored
        $sql = 'INSERT IGNORE INTO friend_request (user_id, friend_user_id)
                VALUES (?, ?)';
        
        $query = $this->GetDbConnection()->NewQuery($sql);
        
        $query->AddIntegerParam($userId);
        $query->AddIntegerParam($friendId);

        $result = $query->TryExecuteInsert();
        return $result !== false;
    }

    public function
    DeleteRequest($userId, $friendId)
    {
        $sql = 'DELETE FROM friend_request
                WHERE (user_id = ? AND friend_user_id = ?)
                OR    (user_id = ? AND friend_user_id = ?)';
        
        $query = $this->GetDbConnection()->NewQuery($sql);

        $query->AddIntegerParam($userId);
        $query->AddIntegerParam($friendId);
        $query->AddIntegerParam($friendId);
        $query->AddIntegerParam($userId);

        return $query->TryExecuteDelete();
    }
}
