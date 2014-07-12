<?php

namespace Site\Models;

use Site\Config\SiteConfig;
use Reverb\System\ModelBase;

class FriendModel 
    extends ModelBase
{
    public function
    __construct()
    {
        $this->modelName = "friendship";
    }

    public function
    GetAllFriendsByUserId($userId)
    {
        $sql = 'SELECT friend_user_id, username
                FROM   friendship
                JOIN user ON user.id = friend_user_id
                WHERE  user_id = ?';

        $query = $this->GetDbConnection()->NewQuery($sql);

        $query->AddStringParam($userId);

        return $query->TryReadDictionary();
    }

    // Get all the people who have sent friend requests to the user id provided
    public function
    GetAllFriendRequestsByUserId($userId)
    {
        $sql = 'SELECT user_id, username
                FROM   friend_request
                JOIN   user ON user.id = friend_request.user_id
                WHERE  friend_user_id = ?';

        $query = $this->GetDbConnection()->NewQuery($sql);

        $query->AddStringParam($userId);

        return $query->TryReadDictionary();
    }

    // Get all the people who have been sent friend requests by the user id provided
    public function
    GetAllRequestedFriendsByUserId($userId)
    {
        $sql = 'SELECT friend_user_id, username
                FROM   friend_request
                JOIN   user ON user.id = friend_request.friend_user_id
                WHERE  friend_request.user_id = ?';

        $query = $this->GetDbConnection()->NewQuery($sql);

        $query->AddStringParam($userId);

        return $query->TryReadDictionary();
    }

    public function
    AddNewFriend(
        $userId, 
        $friendUserId) 
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
