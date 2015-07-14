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
        $userModel = $dependencyContainer->GetInstance('UserModel');

        return new Friends($userModel);
    }
}