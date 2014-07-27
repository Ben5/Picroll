<?php

namespace Site\Components;

use Reverb\System\ComponentBase;
use Site\Config\SiteConfig;
use Site\Models\FriendModel;
use Site\Models\FriendRequestModel;
use Site\Models\UserModel;
use Site\Models\Service\UserModelAwareInterface;
use Site\Models\Service\FriendModelAwareInterface;
use Site\Models\Service\FriendRequestModelAwareInterface;

class Friends extends ComponentBase
    implements UserModelAwareInterface, FriendModelAwareInterface, FriendRequestModelAwareInterface
{
    private $minimumSearchTermLength = 3;
    private $friendModel;
    private $friendRequestModel;
    private $userModel;

    public function GetFriendModel()
    {
        return $this->friendModel;
    }

    public function SetFriendModel(FriendModel $instance)
    {
        $this->friendModel = $instance;
    }

    public function GetFriendRequestModel()
    {
        return $this->friendRequestModel;
    }

    public function SetFriendRequestModel(FriendRequestModel $instance)
    {
        $this->friendRequestModel = $instance;
    }

    public function GetUserModel()
    {
        return $this->userModel;
    }

    public function SetUserModel(UserModel $instance)
    {
        $this->userModel = $instance;
    }

    protected function
    RequiresAuthentication()
    {
        return true;
    }

    protected function
    Index($params)
    {
        $friendModel        = $this->GetFriendModel();
        $friendRequestModel = $this->GetFriendRequestModel();
        $userId             = $_SESSION['user_id'];

        $allFriends        = $friendModel->GetAllFriendsByUserId($userId);
        $allFriendRequests = $friendRequestModel->GetAllFriendRequestsByUserId($userId);
        $allPendingFriends = $friendRequestModel->GetAllRequestedFriendsByUserId($userId);

        $this->ExposeVariable('friends',  $allFriends); 
        $this->ExposeVariable('requests', $allFriendRequests); 
        $this->ExposeVariable('pending',  $allPendingFriends); 
    }

    protected function
    SearchForFriend($params)
    {
        $searchTerm = $params['searchTerm'];

        $searchResult = array();
        if (strlen($searchTerm) >= $this->minimumSearchTermLength) {
            $userModel = $this->GetUserModel();
            $friendModel = $this->GetFriendModel();
            $userId       = $_SESSION['user_id'];

            $searchResult    = $userModel->SearchUsersByNameOrEmail($params['searchTerm']);
            $existingFriends = $friendModel->GetAllFriendsByUserId($userId);
            $friendRequests  = $friendModel->GetAllRequestedFriendsByUserId($userId);

            // remove the existing friends and requests from the search result
            $searchResult = array_diff($searchResult, $existingFriends);
            //var_dump($searchResult);
            $searchResult = array_diff($searchResult, $friendRequests);
            //var_dump($searchResult);
        }

        $this->ExposeVariable('result', $searchResult);
    }

    protected function
    SendFriendRequest($params)
    {
        $userId = $_SESSION['user_id'];

        $friendRequestModel = $this->GetDependencyContainer()->GetInstance('FriendRequestModel');
        $success = $friendRequestModel->CreateNewFriendRequest($userId, $params['friendId']);

        $this->ExposeVariable('result', $success);
    }
    
    protected function
    AcceptFriendRequest($params)
    {
        $userId   = $_SESSION['user_id'];
        $friendId = $params['friendId'];

        $friendModel = $this->GetFriendModel();
        $success = $friendModel->AddNewFriend($userId, $friendId);

        if ($success !== false) {
            $friendRequestModel = $this->GetDependencyContainer()->GetInstance('FriendRequestModel');
            $success = $friendRequestModel->DeleteRequest($userId, $friendId);
        }

        $this->ExposeVariable('result', $success);
    }
}
