<?php

namespace Site\Models\Service;

use Site\Models\UserModel;

interface UserModelAwareInterface
{
    public function GetUserModel();
    public function SetUserModel(UserModel $instance);
}
