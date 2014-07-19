<?php

namespace Site\Models\Service;

use Site\Models\FriendRequestModel;

interface FriendRequestModelAwareInterface
{
    public function GetFriendRequestModel();
    public function SetFriendRequestModel(FriendRequestModel $instance);
}
