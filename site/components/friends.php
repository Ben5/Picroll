<?php

use Picroll\SiteConfig;

include(SiteConfig::REVERB_ROOT."/system/componentbase.php");
include(SiteConfig::SITE_ROOT."/models/friend.php");

class Friends extends ComponentBase
{
    protected function
    RequiresAuthentication()
    {
        return true;
    }

    protected function 
    Index($params)
    {
        $friendModel = new FriendModel();
        $userId      = $_SESSION['user_id'];

        $allFriends  = $friendModel->GetAllFriendIdsByUserId($userId);

        $this->ExposeVariable("friends", $allFriends); 
    }

}
