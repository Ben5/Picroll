<?php

use Picroll\SiteConfig;

require_once SiteConfig::REVERB_ROOT."/system/modelbase.php";
require_once SiteConfig::REVERB_ROOT."/lib/DbInterface.php";

class FriendRequestModel extends ModelBase
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
        
        $query = DbInterface::NewQuery($sql);
        
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
        
        $query = DbInterface::NewQuery($sql);

        $query->AddIntegerParam($userId);
        $query->AddIntegerParam($friendId);
        $query->AddIntegerParam($friendId);
        $query->AddIntegerParam($userId);

        return $query->TryExecuteDelete();
    }
}