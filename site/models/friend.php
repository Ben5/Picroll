<?php

use Picroll\SiteConfig;

require_once SiteConfig::REVERB_ROOT."/system/modelbase.php";
require_once SiteConfig::REVERB_ROOT."/lib/DbInterface.php";

class FriendModel extends ModelBase
{
    public function
    __construct()
    {
       $this->modelName = "friendship";
    }

    public function
    GetAllFriendIdsByUserId($userId)
    {
        $sql = 'SELECT friend_user_id
                FROM   friendship
                WHERE  user_id = ?';

        $query = DbInterface::NewQuery($sql);

        $query->AddStringParam($userId);

        return $query->TryReadSingleColumn();
    }

    public function
    AddNewFriend(
       $userId, 
       $friendUserId)
    {
        // we add 2 friendships, so that we can simplfy searching for a user's friends.
        $sql = "INSERT INTO friendship (user_id, friend_user_id)
                VALUES (?, ?), (?, ?)";

        $query = DbInterface::NewQuery($sql);
        $query->AddStringParam($userId);
        $query->AddStringParam($friendUserId);
        $query->AddStringParam($friendUserId);
        $query->AddStringParam($userId);

        return $query->TryExecuteInsert();
    }
}
