<?php

namespace Site\Models\Service;

use Site\Models\FriendModel;

interface FriendModelAwareInterface
{
    public function GetFriendModel();
    public function SetFriendModel(FriendModel $instance);
}
