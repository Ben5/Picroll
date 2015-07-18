<?php
/**
 * Created by PhpStorm.
 * User: bensmith
 * Date: 14/07/15
 * Time: 13:15
 */

namespace Site\Components\Service;

use Reverb\System\DependencyContainer;
use Site\Components\Friends;

class FriendsFactory
{
    public function CreateInstance(DependencyContainer $dependencyContainer)
    {
        $friendModel        = $dependencyContainer->GetInstance('FriendModel');
        $friendRequestModel = $dependencyContainer->GetInstance('FriendRequestModel');
        $userModel          = $dependencyContainer->GetInstance('UserModel');

        return new Friends($friendModel, $friendRequestModel, $userModel);
    }
}